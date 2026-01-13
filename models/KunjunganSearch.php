<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Kunjungan;

/**
 * KunjunganSearch represents the model behind the search form about `app\models\Kunjungan`.
 */
class KunjunganSearch extends Kunjungan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kunjungan_id', 'klinik_id'], 'integer'],
            [['baru_lama','mr', 'tanggal_periksa', 'jam_masuk', 'jam_selesai', 'status', 'created', 'user_input','pasien_nama','medunit_cd','cara_id','ruang_cd','dpjp','status_rm'], 'safe'],
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
    public function searchDrPemeriksaan($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = Kunjungan::find();

        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');
        
        $query->where(['tipe_kunjungan'=>'Rawat Jalan']);
        $medunit = Yii::$app->user->identity->medunit_cd;
        if(!empty($medunit)){
            $query->andFilterWhere(['kunjungan.medunit_cd'=>$medunit]);
        }


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
        
        
        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'baru_lama' => $this->baru_lama,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'cara_nama', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        $query->andFilterWhere(['<>', 'kunjungan.status', 'selesai']);

        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit;
        // if(!isset($params['sort']))
        //     $query->orderBy('jam_masuk ASC');
        
        // if($sortby==[])        
        //     $query->orderBy('jam_masuk DESC');
        // else
        //     $query->orderBy($sortby);

        return $dataProvider;
    }

    public function searchBangsalPemeriksaan($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = Kunjungan::find();

        //if(empty($this->tanggal_periksa))
        //    $this->tanggal_periksa = date('Y-m-d');

        $query->joinWith('rekamMedis');
        $query->where(['tipe_kunjungan'=>'Rawat Inap']);
        $bangsal = Yii::$app->user->identity->bangsal_cd;
        if(!empty($bangsal))
            $query->andFilterWhere(['ruang.bangsal_cd'=>$bangsal]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if($join_bayar) $query->joinWith('bayar');
        
        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');

        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'baru_lama' => $this->baru_lama,
            //'kunjungan.created' => $this->created,
        ]);



        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'cara_nama', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        $query->andFilterWhere(['<>', 'kunjungan.status', 'selesai']);

        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit;
        // if(!isset($params['sort']))
        //     $query->orderBy('jam_masuk ASC');
        
        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }

    public function search($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = Kunjungan::find();
        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');
        if(!empty($jenis)) {
            $jenis_nm = $jenis=='rj' ? 'Rawat Jalan' : 'Rawat Inap';
            $query->where(['tipe_kunjungan'=>$jenis_nm]);

            if($jenis=='ri'){
                $this->tanggal_periksa = null;
            }
        }

        $medunit = Yii::$app->user->identity->medunit_cd;
        if(!empty($medunit)){
            $query->andFilterWhere(['kunjungan.medunit_cd'=>$medunit]);
        }

        $bangsal = Yii::$app->user->identity->bangsal_cd;
        if(!empty($bangsal))
            $query->andFilterWhere(['ruang.bangsal_cd'=>$bangsal]);


        if(!empty($status))
            $query->andFilterWhere(['kunjungan.status'=>$status]);

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
        
        
        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'baru_lama' => $this->baru_lama,
            'created' => $this->created,
        ]);



        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'cara_nama', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        if($status=='pemeriksaan'){
            $query->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']]);
        } else {
            $query->andFilterWhere(['status'=>$this->status]);
        }
        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit;
        // if(!isset($params['sort']))
        //     $query->orderBy('jam_masuk ASC');
        
        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }


    public function searchFarmasi($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = Kunjungan::find();

        if(!empty($status))
            $query->andFilterWhere(['kunjungan.status'=>$status]);

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
        
        
        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'baru_lama' => $this->baru_lama,
            'created' => $this->created,
        ]);



        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'cara_nama', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        if($status=='pemeriksaan'){
            $query->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']]);
        } else {
            $query->andFilterWhere(['status'=>$this->status]);
        }
        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }

    public function searchFarmasiRajal($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = Kunjungan::find();
        $query->joinWith('rekamMedis');
        $query->where(['tipe_kunjungan'=>'Rawat Jalan']);
        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');
        

        if(!empty($status))
            $query->andFilterWhere(['kunjungan.status'=>$status]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if($join_bayar) $query->joinWith('bayar');
        
        
        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        $query->andFilterWhere([
            'tanggal_periksa' => $this->tanggal_periksa,
            'jam_masuk' => $this->jam_masuk,
            'jam_selesai' => $this->jam_selesai,
            'baru_lama' => $this->baru_lama,
            'created' => $this->created,
        ]);



        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'cara_nama', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        if($status=='pemeriksaan'){
            $query->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']]);
        } else {
            $query->andFilterWhere(['status'=>$this->status]);
        }
        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }

    public function searchFarmasiRanap($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = RekamMedis::find();
        $query->joinWith('kunjungan k');
        $query->where(['k.tipe_kunjungan'=>'Rawat Inap']);

        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        
        $query->joinWith('ruang');
        $query->joinWith('mr0');
        $query->joinWith('dpjp a');
    
        

        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);
        
        $query->orderBy('created DESC');

        return $dataProvider;
    }


    public function searchRjPendaftaran($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $query = Kunjungan::find();
        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');

        if(!empty($jenis)) {
            $jenis_nm = $jenis=='rj' ? 'Rawat Jalan' : 'Rawat Inap';
            $query->where(['tipe_kunjungan'=>$jenis_nm]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        
        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'kunjungan.cara_id', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        $query->andFilterWhere(['IN', 'kunjungan.status', ['antri','diperiksa','antri bayar']]);

        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }

    public function searchTrackRm($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {
        $jenis = 'rj';
        $query = Kunjungan::find();
        if(empty($this->tanggal_periksa))
            $this->tanggal_periksa = date('Y-m-d');

        if(!empty($jenis)) {
            $jenis_nm = $jenis=='rj' ? 'Rawat Jalan' : 'Rawat Inap';
            $query->where(['tipe_kunjungan'=>$jenis_nm]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        
        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'kunjungan.cara_id', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        $query->andFilterWhere(['IN', 'kunjungan.status', ['antri','diperiksa','antri bayar']]);

        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }

    public function searchRiPendaftaran($params,$join_rm = false,$join_bayar = false,$status = '',$jenis=null,array $sortby=[])
    {

        $query = Kunjungan::find();

        // if(empty($this->tanggal_periksa))
        //     $this->tanggal_periksa = date('Y-m-d');

        if(!empty($jenis)) {
            $jenis_nm = $jenis=='rj' ? 'Rawat Jalan' : 'Rawat Inap';
            $query->where(['tipe_kunjungan'=>$jenis_nm]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('ruang0');
        $query->joinWith('mr0');
        $query->joinWith('unit');
        $query->joinWith('cara');
        $query->joinWith('dokter');
        
        $query->andFilterWhere(['like', 'pasien.mr', $this->mr])
            ->andFilterWhere(['like', 'pasien.nama', $this->pasien_nama])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'status_rm', $this->status_rm])
            ->andFilterWhere(['like', 'cara_id', $this->cara_id])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_cd])
            ->andFilterWhere(['like', 'dokter.nama', $this->dpjp])
            ->andFilterWhere(['like', 'tanggal_periksa', $this->tanggal_periksa])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_cd]);

        $query->andFilterWhere(['IN', 'kunjungan.status', ['antri','diperiksa','antri bayar']]);

        if($sortby==[])        
            $query->orderBy('jam_masuk DESC');
        else
            $query->orderBy($sortby);

        return $dataProvider;
    }
}
