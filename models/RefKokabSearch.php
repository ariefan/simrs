<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RefKokab;

/**
 * RefKokabSearch represents the model behind the search form about `app\models\RefKokab`.
 */
class RefKokabSearch extends RefKokab
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kota_id', 'kokab_nama', 'provinsi_id'], 'safe'],
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
        $query = RefKokab::find();

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
        $query->andFilterWhere(['like', 'kota_id', $this->kota_id])
            ->andFilterWhere(['like', 'kokab_nama', $this->kokab_nama])
            ->andFilterWhere(['like', 'provinsi_id', $this->provinsi_id]);

        return $dataProvider;
    }
}
