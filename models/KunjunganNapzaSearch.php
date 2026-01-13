<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kunjungan;
use app\models\RmLabNapza;

/**
 * KunjunganSearch represents the model behind the search form about `app\models\Kunjungan`.
 */
class KunjunganNapzaSearch extends Kunjungan
{
    /**
     * @inheritdoc
     */
    private $medicalunit_cd = 'A055';
    public function rules()
    {
        return [
            [['kunjungan_id', 'klinik_id'], 'integer'],
            [['baru_lama','mr', 'tanggal_periksa', 'jam_masuk', 'jam_selesai', 'status', 'created', 'user_input','pasien_nama','medunit_cd'], 'safe'],
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
    public function search($params,$join_rm = false,$status = '')
    {
        $query = Kunjungan::find();

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
        
        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');
        
        $query->joinWith('unit');
        $query->leftJoin('pasien','pasien.mr = kunjungan.mr');
        $query->leftJoin('rekam_medis','rekam_medis.kunjungan_id = kunjungan.kunjungan_id');
        $query->leftJoin('rm_lab','rekam_medis.rm_id = rm_lab.rm_id');
        $query->andFilterWhere(['rm_lab.medicalunit_cd'=>$this->medicalunit_cd]);
        $query->andFilterWhere(['NOT IN', 'rekam_medis.rm_id',RmLabNapza::find()->select('rm_id')/*'select rm_id from rm_lab_napza'*/]);
        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'baru_lama' => $this->baru_lama,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd]);

        if($status=='pemeriksaan'){
            $query->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']]);
        } else {
            $query->andFilterWhere(['status'=>$this->status]);
        }
        if(!isset($params['sort']))
            $query->orderBy('jam_masuk ASC');
        
        return $dataProvider;
    }
}
