<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RefProvinsi;

/**
 * RefProvinsiSearch represents the model behind the search form about `app\models\RefProvinsi`.
 */
class RefProvinsiSearch extends RefProvinsi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provinsi_id', 'provinsi_nama'], 'safe'],
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
        $query = RefProvinsi::find();

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
        $query->andFilterWhere(['like', 'provinsi_id', $this->provinsi_id])
            ->andFilterWhere(['like', 'provinsi_nama', $this->provinsi_nama]);

        return $dataProvider;
    }
}
