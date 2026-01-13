<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RlGrouping39;

/**
 * RlGrouping39Search represents the model behind the search form about `app\models\RlGrouping39`.
 */
class RlGrouping39Search extends RlGrouping39
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rl_ref_39_no', 'tindakan_cd'], 'safe'],
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
        $query = RlGrouping39::find();

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
        $query->andFilterWhere(['like', 'rl_ref_39_no', $this->rl_ref_39_no])
            ->andFilterWhere(['like', 'tindakan_cd', $this->tindakan_cd]);

        return $dataProvider;
    }
}
