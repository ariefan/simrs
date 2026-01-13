<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TarifTpItem;

/**
 * TarifTpItemSearch represents the model behind the search form about `app\models\TarifTpItem`.
 */
class TarifTpItemSearch extends TarifTpItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tariftp_no', 'seq_no', 'trx_tarif_seqno'], 'integer'],
            [['item_nm', 'tarif_tp', 'modi_id', 'modi_datetime'], 'safe'],
            [['tarif_item', 'quantity'], 'number'],
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
        $query = TarifTpItem::find();

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
            'tariftp_no' => $this->tariftp_no,
            'seq_no' => $this->seq_no,
            'trx_tarif_seqno' => $this->trx_tarif_seqno,
            'tarif_item' => $this->tarif_item,
            'quantity' => $this->quantity,
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->andFilterWhere(['like', 'item_nm', $this->item_nm])
            ->andFilterWhere(['like', 'tarif_tp', $this->tarif_tp])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
