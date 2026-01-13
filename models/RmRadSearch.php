<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RmRad;

/**
 * RmRadSearch represents the model behind the search form about `app\models\RmRad`.
 */
class RmRadSearch extends RmRad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rm_id', 'dokter'], 'integer'],
            [['medicalunit_cd', 'nama', 'catatan', 'hasil', 'dokter_nama'], 'safe'],
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
        $query = RmRad::find();

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
            'dokter' => $this->dokter,
        ]);

        $query->andFilterWhere(['like', 'medicalunit_cd', $this->medicalunit_cd])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'hasil', $this->hasil])
            ->andFilterWhere(['like', 'dokter_nama', $this->dokter_nama]);

        return $dataProvider;
    }
}
