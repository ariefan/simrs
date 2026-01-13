<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvBatchItem;

/**
 * InvBatchItemSearch represents the model behind the search form about `app\models\InvBatchItem`.
 */
class InvBatchItemSearch extends InvBatchItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_no', 'buy_price', 'sell_price', 'sell_price_2', 'order_id'], 'integer'],
            [['pos_cd', 'item_cd', 'supplier', 'batch_no_start', 'batch_no_end', 'expire_date', 'modi_id', 'modi_datetime'], 'safe'],
            [['trx_qty'], 'number'],
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
        $query = InvBatchItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'batch_no' => $this->batch_no,
            'trx_qty' => $this->trx_qty,
            'buy_price' => $this->buy_price,
            'sell_price' => $this->sell_price,
            'sell_price_2' => $this->sell_price_2,
            'expire_date' => $this->expire_date,
            'order_id' => $this->order_id,
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->andFilterWhere(['like', 'pos_cd', $this->pos_cd])
            ->andFilterWhere(['like', 'item_cd', $this->item_cd])
            ->andFilterWhere(['like', 'supplier', $this->supplier])
            ->andFilterWhere(['like', 'batch_no_start', $this->batch_no_start])
            ->andFilterWhere(['like', 'batch_no_end', $this->batch_no_end])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
