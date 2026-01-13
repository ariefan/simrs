<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Account;

/**
 * AccountSearch represents the model behind the search form about `app\models\Account`.
 */
class AccountSearch extends Account
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_cd', 'accgroup_cd', 'account_nm', 'print_single_st'], 'safe'],
            [['default_amount'], 'number'],
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
        $query = Account::find();

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
            'default_amount' => $this->default_amount,
            'order_no' => $this->order_no,
        ]);

        $query->andFilterWhere(['like', 'account_cd', $this->account_cd])
            ->andFilterWhere(['like', 'accgroup_cd', $this->accgroup_cd])
            ->andFilterWhere(['like', 'account_nm', $this->account_nm])
            ->andFilterWhere(['like', 'print_single_st', $this->print_single_st]);

        return $dataProvider;
    }
}
