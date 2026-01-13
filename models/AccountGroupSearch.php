<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AccountGroup;

/**
 * AccountGroupSearch represents the model behind the search form about `app\models\AccountGroup`.
 */
class AccountGroupSearch extends AccountGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accgroup_cd', 'accgroup_nm'], 'safe'],
            [['order_no'], 'integer'],
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
        $query = AccountGroup::find();

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
            'order_no' => $this->order_no,
        ]);

        $query->andFilterWhere(['like', 'accgroup_cd', $this->accgroup_cd])
            ->andFilterWhere(['like', 'accgroup_nm', $this->accgroup_nm]);

        return $dataProvider;
    }
}
