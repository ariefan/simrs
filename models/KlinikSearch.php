<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Klinik;

/**
 * KlinikSearch represents the model behind the search form about `app\models\Klinik`.
 */
class KlinikSearch extends Klinik
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['klinik_id', 'maximum_row', 'luas_tanah', 'luas_bangunan'], 'integer'],
            [['klinik_nama', 'region_cd', 'kode_pos', 'fax', 'email', 'website', 'alamat', 'nomor_telp_1', 'nomor_telp_2', 'kepala_klinik', 'izin_no', 'izin_tgl', 'izin_oleh', 'izin_sifat', 'izin_masa_berlaku'], 'safe'],
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
        $query = Klinik::find();

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
            'klinik_id' => $this->klinik_id,
            'maximum_row' => $this->maximum_row,
            'luas_tanah' => $this->luas_tanah,
            'luas_bangunan' => $this->luas_bangunan,
            'izin_tgl' => $this->izin_tgl,
            'izin_masa_berlaku' => $this->izin_masa_berlaku,
        ]);

        $query->andFilterWhere(['like', 'klinik_nama', $this->klinik_nama])
            ->andFilterWhere(['like', 'region_cd', $this->region_cd])
            ->andFilterWhere(['like', 'kode_pos', $this->kode_pos])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'nomor_telp_1', $this->nomor_telp_1])
            ->andFilterWhere(['like', 'nomor_telp_2', $this->nomor_telp_2])
            ->andFilterWhere(['like', 'kepala_klinik', $this->kepala_klinik])
            ->andFilterWhere(['like', 'izin_no', $this->izin_no])
            ->andFilterWhere(['like', 'izin_oleh', $this->izin_oleh])
            ->andFilterWhere(['like', 'izin_sifat', $this->izin_sifat]);

        return $dataProvider;
    }
}
