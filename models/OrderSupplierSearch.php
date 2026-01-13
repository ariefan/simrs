<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderSupplier;

/**
 * OrderSupplierSearch represents the model behind the search form about `app\models\OrderSupplier`.
 */
class OrderSupplierSearch extends OrderSupplier
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'total_harga', 'user_id'], 'integer'],
            [['order_kode', 'supplier_cd', 'order_tanggal', 'status', 'catatan', 'created', 'modified'], 'safe'],
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
        $query = OrderSupplier::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['modified' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'order_id' => $this->order_id,
            'order_tanggal' => $this->order_tanggal,
            'total_harga' => $this->total_harga,
            'user_id' => $this->user_id,
            'created' => $this->created,
            'modified' => $this->modified,
        ]);

        $query->joinWith('supplier');
        $query->andFilterWhere(['like', 'order_kode', $this->order_kode])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'supplier_nm', $this->supplier_cd])
            ->andFilterWhere(['like', 'catatan', $this->catatan]);

        return $dataProvider;
    }
}
