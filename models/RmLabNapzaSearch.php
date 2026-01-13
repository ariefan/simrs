<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RmLabNapza;

/**
 * RmLabNapzaSearch represents the model behind the search form about `app\models\RmLabNapza`.
 */
class RmLabNapzaSearch extends RmLabNapza
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lab_napza_id', 'rm_id'], 'integer'],
            [['nomor_surat', 'tanggal_surat', 'permintaan', 'keperluan', 'tanggal_periksa', 'created'], 'safe'],
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
        $query = RmLabNapza::find();

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
            'lab_napza_id' => $this->lab_napza_id,
            'rm_id' => $this->rm_id,
            'tanggal_surat' => $this->tanggal_surat,
            'tanggal_periksa' => $this->tanggal_periksa,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'nomor_surat', $this->nomor_surat])
            ->andFilterWhere(['like', 'permintaan', $this->permintaan])
            ->andFilterWhere(['like', 'keperluan', $this->keperluan]);

        return $dataProvider;
    }
}
