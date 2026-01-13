<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Asuransi;

/**
 * AsuransiSearch represents the model behind the search form about `app\models\Asuransi`.
 */
class AsuransiSearch extends Asuransi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['insurance_cd', 'insurance_nm'], 'safe'],
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
        $query = Asuransi::find();

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
        $query->andFilterWhere(['like', 'insurance_cd', $this->insurance_cd])
            ->andFilterWhere(['like', 'insurance_nm', $this->insurance_nm]);

        return $dataProvider;
    }
}
