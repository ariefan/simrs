<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ruang;

/**
 * RuangSearch represents the model behind the search form about `app\models\Ruang`.
 */
class RuangSearch extends Ruang
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruang_cd', 'kelas_cd', 'bangsal_cd', 'ruang_nm', 'status'], 'safe'],
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
        $query = Ruang::find();

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
        $query->andFilterWhere(['like', 'ruang_cd', $this->ruang_cd])
            ->andFilterWhere(['like', 'kelas_cd', $this->kelas_cd])
            ->andFilterWhere(['like', 'bangsal_cd', $this->bangsal_cd])
            ->andFilterWhere(['like', 'ruang_nm', $this->ruang_nm])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
