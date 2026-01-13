<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UnitMedis;

/**
 * UnitMedisSearch represents the model behind the search form about `app\models\UnitMedis`.
 */
class UnitMedisSearch extends UnitMedis
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medunit_cd', 'medunit_nm', 'medunit_tp'], 'safe'],
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
        $query = UnitMedis::find();

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
        $query->andFilterWhere(['like', 'medunit_cd', $this->medunit_cd])
            ->andFilterWhere(['like', 'medunit_nm', $this->medunit_nm])
            ->andFilterWhere(['like', 'medunit_tp', $this->medunit_tp]);

        return $dataProvider;
    }
}
