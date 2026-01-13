<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvItemMove;

/**
 * InvItemMoveSearch represents the model behind the search form about `app\models\InvItemMove`.
 */
class InvItemMoveSearch extends InvItemMove
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['pos_cd', 'pos_destination', 'item_cd', 'trx_by', 'trx_datetime', 'purpose', 'vendor', 'move_tp', 'note', 'modi_id', 'modi_datetime'], 'safe'],
            [['trx_qty', 'old_stock', 'new_stock'], 'number'], 
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
    public function search($params, $move_tp='In')
    {
        $query = InvItemMove::find()->where(['move_tp' => $move_tp]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort'=> ['defaultOrder' => ['modi_datetime' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'trx_datetime' => $this->trx_datetime,
            'trx_qty' => $this->trx_qty,
            'old_stock' => $this->old_stock,
            'new_stock' => $this->new_stock,
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->joinWith('itemCd');
        $query->andFilterWhere(['like', 'pos_cd', $this->pos_cd])
            ->andFilterWhere(['like', 'pos_destination', $this->pos_destination])
            ->andFilterWhere(['like', 'item_nm', $this->item_cd])
            ->andFilterWhere(['like', 'trx_by', $this->trx_by])
            ->andFilterWhere(['like', 'purpose', $this->purpose])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'move_tp', $this->move_tp])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
