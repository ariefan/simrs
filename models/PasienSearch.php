<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pasien;

/**
 * PasienSearch represents the model behind the search form about `app\models\Pasien`.
 */
class PasienSearch extends Pasien
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mr', 'nama', 'identitas', 'no_identitas', 'region_cd', 'warga_negara', 'tanggal_lahir', 'jk', 'alamat', 'kode_pos', 'no_telp', 'pendidikan', 'pekerjaan', 'suku', 'agama', 'pj_nama', 'gol_darah', 'pj_hubungan', 'pj_alamat', 'pj_telpon', 'nama_ayah', 'nama_ibu', 'email', 'foto', 'created', 'modified', 'user_input', 'user_modified'], 'safe'],
            [['klinik_id', 'berat', 'tinggi'], 'integer'],
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
        $query = Pasien::find()->orderBy('mr DESC');

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
            'tanggal_lahir' => $this->tanggal_lahir,
            'berat' => $this->berat,
            'tinggi' => $this->tinggi,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->andFilterWhere(['like', 'mr', $this->mr])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'identitas', $this->identitas])
            ->andFilterWhere(['like', 'no_identitas', $this->no_identitas])
            ->andFilterWhere(['like', 'region_cd', $this->region_cd])
            ->andFilterWhere(['like', 'warga_negara', $this->warga_negara])
            ->andFilterWhere(['like', 'jk', $this->jk])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'kode_pos', $this->kode_pos])
            ->andFilterWhere(['like', 'no_telp', $this->no_telp])
            ->andFilterWhere(['like', 'pendidikan', $this->pendidikan])
            ->andFilterWhere(['like', 'pekerjaan', $this->pekerjaan])
            ->andFilterWhere(['like', 'suku', $this->suku])
            ->andFilterWhere(['like', 'agama', $this->agama])
            ->andFilterWhere(['like', 'pj_nama', $this->pj_nama])
            ->andFilterWhere(['like', 'gol_darah', $this->gol_darah])
            ->andFilterWhere(['like', 'pj_hubungan', $this->pj_hubungan])
            ->andFilterWhere(['like', 'pj_alamat', $this->pj_alamat])
            ->andFilterWhere(['like', 'pj_telpon', $this->pj_telpon])
            ->andFilterWhere(['like', 'nama_ayah', $this->nama_ayah])
            ->andFilterWhere(['like', 'nama_ibu', $this->nama_ibu])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'user_input', $this->user_input])
            ->andFilterWhere(['like', 'user_modified', $this->user_modified]);

        return $dataProvider;
    }
}
