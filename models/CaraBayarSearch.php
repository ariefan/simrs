<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CaraBayar;

/**
 * CaraBayarSearch represents the model behind the search form about `app\models\CaraBayar`.
 */
class CaraBayarSearch extends CaraBayar
{
    /**
     * @inheritdoc
     */
    public $harga_obat;
    public function rules()
    {
        return [
            [['cara_id'], 'integer'],
            [['cara_nama', 'cara_tipe', 'harga_obat'], 'safe'],
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
        $query = CaraBayar::find();

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
            'cara_id' => $this->cara_id,
        ]);

        $query->andFilterWhere(['like', 'cara_nama', $this->cara_nama])
            ->andFilterWhere(['like', 'cara_tipe', $this->cara_tipe])
            ->andFilterWhere(['like', 'harga_obat', $this->harga_obat]);

        return $dataProvider;
    }
}
