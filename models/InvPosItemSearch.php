<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvPosItem;

/**
 * InvPosItemSearch represents the model behind the search form about `app\models\InvPosItem`.
 */
class InvPosItemSearch extends InvPosItem
{
    public $item_nm;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_cd', 'item_cd', 'modi_id', 'modi_datetime','item_nm'], 'safe'],
            [['quantity'], 'number'],
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
        $query = InvPosItem::find();

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
        $query->joinWith('item');
        $query->joinWith('pos');
        // grid filtering conditions
        $query->andFilterWhere([
            'quantity' => $this->quantity,
            'modi_datetime' => $this->modi_datetime,
            'inv_pos_item.item_cd' => $this->item_cd,
        ]);

        $query->andFilterWhere(['like', 'pos_nm', $this->pos_cd])
            ->andFilterWhere(['like', 'item_nm', $this->item_nm])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
