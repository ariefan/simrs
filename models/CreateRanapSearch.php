<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kunjungan;

/**
 * KunjunganSearch represents the model behind the search form about `app\models\Kunjungan`.
 */
class CreateRanapSearch extends Kunjungan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kunjungan_id', 'klinik_id'], 'integer'],
            [['canMutasi','mr', 'tanggal_periksa', 'jam_masuk', 'jam_selesai', 'status', 'created', 'user_input','pasien_nama','medunit_cd'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null)
    {
        $query = Kunjungan::find();
        
        if(!empty($jenis)) {
            $jenis_nm = $jenis=='rj' ? 'Rawat Jalan' : 'Rawat Inap';
            $query->where(['tipe_kunjungan'=>$jenis_nm]);
        }
        // add conditions that should always apply here
        if(!empty($status))
            $this->status = $status;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if($join_rm) $query->joinWith('rekamMedis');
        if($join_bayar) $query->joinWith('bayar');
        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');
        
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(["(select count(*) from kunjungan K1 where K1.parent_id = kunjungan.kunjungan_id)"=>0])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd]);

        if($status=='pemeriksaan'){
            $query->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']]);
        } else {
            $query->andFilterWhere(['status'=>$this->status]);
        }

        // if(!isset($params['sort']))
        //     $query->orderBy('jam_masuk ASC');
    
        $query->orderBy(['jam_masuk'=>SORT_DESC]);
        
        return $dataProvider;
    }
}
