<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvItemMaster;

/**
 * InvItemMasterSearch represents the model behind the search form about `app\models\InvItemMaster`.
 */
class InvItemMasterSearch extends InvItemMaster
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_cd', 'type_cd', 'unit_cd', 'item_nm', 'barcode', 'currency_cd', 'vat_tp', 'generic_st', 'active_st', 'inventory_st', 'tariftp_cd', 'last_user', 'last_update'], 'safe'],
            [['item_price_buy', 'item_price', 'ppn', 'reorder_point', 'minimum_stock', 'maximum_stock'], 'number'],
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
        $query = InvItemMaster::find();

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
            'item_price_buy' => $this->item_price_buy,
            'item_price' => $this->item_price,
            'ppn' => $this->ppn,
            'reorder_point' => $this->reorder_point,
            'minimum_stock' => $this->minimum_stock,
            'maximum_stock' => $this->maximum_stock,
            'last_update' => $this->last_update,
        ]);

        $query->andFilterWhere(['like', 'item_cd', $this->item_cd])
            ->andFilterWhere(['like', 'type_cd', $this->type_cd])
            ->andFilterWhere(['like', 'unit_cd', $this->unit_cd])
            ->andFilterWhere(['like', 'item_nm', $this->item_nm])
            ->andFilterWhere(['like', 'barcode', $this->barcode])
            ->andFilterWhere(['like', 'currency_cd', $this->currency_cd])
            ->andFilterWhere(['like', 'vat_tp', $this->vat_tp])
            ->andFilterWhere(['like', 'generic_st', $this->generic_st])
            ->andFilterWhere(['like', 'active_st', $this->active_st])
            ->andFilterWhere(['like', 'inventory_st', $this->inventory_st])
            ->andFilterWhere(['like', 'tariftp_cd', $this->tariftp_cd])
            ->andFilterWhere(['like', 'last_user', $this->last_user]);

        return $dataProvider;
    }
}
