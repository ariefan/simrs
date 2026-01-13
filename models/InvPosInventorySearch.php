<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvPosInventory;

/**
 * InvPosInventorySearch represents the model behind the search form about `app\models\InvPosInventory`.
 */
class InvPosInventorySearch extends InvPosInventory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_cd', 'pos_nm', 'pos_root', 'description', 'unit_medis', 'modi_id', 'modi_datetime'], 'safe'],
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
        $query = InvPosInventory::find()
            ->joinWith('bangsal')
            ->joinWith('unitMedis');

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

        $query->andFilterWhere(['like', 'pos_cd', $this->pos_cd])
            ->andFilterWhere(['like', 'pos_nm', $this->pos_nm])
            ->andFilterWhere(['like', 'pos_root', $this->pos_root])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'unit_medis', $this->unit_medis])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
