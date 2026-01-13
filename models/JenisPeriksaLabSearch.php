<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JenisPeriksaLab;

/**
 * JenisPeriksaLabSearch represents the model behind the search form about `app\models\JenisPeriksaLab`.
 */
class JenisPeriksaLabSearch extends JenisPeriksaLab
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periksa_id'], 'integer'],
            [['periksa_nama', 'periksa_group'], 'safe'],
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
        $query = JenisPeriksaLab::find();

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
            'periksa_id' => $this->periksa_id,
        ]);

        $query->andFilterWhere(['like', 'periksa_nama', $this->periksa_nama])
            ->andFilterWhere(['like', 'periksa_group', $this->periksa_group]);

        return $dataProvider;
    }
}
