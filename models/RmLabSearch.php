<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RmLab;
use app\models\unitMedisItem;

/**
 * RmLabSearch represents the model behind the search form about `app\models\RmLab`.
 */
class RmLabSearch extends RmLab
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rm_id', 'dokter'], 'integer'],
            [['medicalunit_cd', 'nama', 'catatan', 'hasil', 'dokter_nama'], 'safe'],
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
    public function search($params)
    {
        //$this->load($params);

        //$SQL = "SELECT b.medicalunit_nm,a.rm_id,a.medicalunit_cd,a.nama,a.catatan,a.hasil FROM rm_lab a, unit_medis_item b where (a.medicalunit_cd=b.medicalunit_cd) and (a.medicalunit_cd like '%$this->medicalunit_cd%') and (a.nama like '%$this->nama%') and ((a.catatan IS NULL) or (a.hasil IS NULL))";
        //$SQL = "select rekam_medis.rm_id as 'No Rekam Medis', pasien.nama as 'Nama Pasien', kunjungan.tanggal_periksa as 'Tanggal Periksa', unit_medis.medunit_nm as 'Unit Pengirim', dokter.nama as 'Dokter Pengirim' from rekam_medis, pasien, dokter, unit_medis, kunjungan where kunjungan.kunjungan_id = rekam_medis.kunjungan_id and kunjungan.medunit_cd = unit_medis.medunit_cd and kunjungan.user_id = dokter.user_id and pasien.mr = kunjungan.mr and kunjungan.user_id = dokter.user_id ";
        //$query = RmLab::findBySql($SQL);
        $query = RmLab::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rm_id' => $this->rm_id,
            'dokter' => $this->dokter,
        ]);

        $query->andFilterWhere(['like', 'medicalunit_cd', $this->medicalunit_cd])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'hasil', $this->hasil])
            ->andFilterWhere(['like', 'dokter_nama', $this->dokter_nama]);
        
        return $dataProvider;
    }
}
