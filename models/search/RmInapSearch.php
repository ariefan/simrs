<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RmInap;

/**
 * RmInapSeach represents the model behind the search form about `app\models\RmInap`.
 */
class RmInapSeach extends RmInap
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rm_id'], 'integer'],
            [['anamnesis', 'pemeriksaan_fisik', 'assesment', 'plan', 'created'], 'safe'],
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
        $query = RmInap::find()->orderBy('created DESC');

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
            'id' => $this->id,
            'rm_id' => $this->rm_id,
            'kunjungan_id'=>$this->kunjungan_id,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'anamnesis', $this->anamnesis])
            ->andFilterWhere(['like', 'pemeriksaan_fisik', $this->pemeriksaan_fisik])
            ->andFilterWhere(['like', 'assesment', $this->assesment])
            ->andFilterWhere(['like', 'plan', $this->plan]);
           


        return $dataProvider;
    }
}
