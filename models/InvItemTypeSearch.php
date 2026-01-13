<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvItemType;

/**
 * InvItemTypeSearch represents the model behind the search form about `app\models\InvItemType`.
 */
class InvItemTypeSearch extends InvItemType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_cd', 'type_nm', 'modi_id', 'modi_datetime'], 'safe'],
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
        $query = InvItemType::find();

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
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->andFilterWhere(['like', 'type_cd', $this->type_cd])
            ->andFilterWhere(['like', 'type_nm', $this->type_nm])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
