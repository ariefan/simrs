<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RmLabNapzaDetail;

/**
 * RmLabNapzaDetailSearch represents the model behind the search form about `app\models\RmLabNapzaDetail`.
 */
class RmLabNapzaDetailSearch extends RmLabNapzaDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['napza_detail_id', 'lab_napza_id', 'periksa_id'], 'integer'],
            [['hasil'], 'safe'],
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
        $query = RmLabNapzaDetail::find();

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
            'napza_detail_id' => $this->napza_detail_id,
            'lab_napza_id' => $this->lab_napza_id,
            'periksa_id' => $this->periksa_id,
        ]);

        $query->andFilterWhere(['like', 'hasil', $this->hasil]);

        return $dataProvider;
    }
}
