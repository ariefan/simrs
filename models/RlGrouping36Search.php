<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RlGrouping36;

/**
 * RlGrouping36Search represents the model behind the search form about `app\models\RlGrouping36`.
 */
class RlGrouping36Search extends RlGrouping36
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rl_ref_36_no'], 'integer'],
            [['tindakan_cd', 'jenis'], 'safe'],
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
        $query = RlGrouping36::find()
            ->select('spesialisasi')
            ->rightJoin('rl_ref_36','rl_ref_36_no=no')
            ->distinct();

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
            'rl_ref_36_no' => $this->rl_ref_36_no,
        ]);

        // $query->andFilterWhere(['like', 'tindakan_cd', $this->tindakan_cd])
        //     ->andFilterWhere(['like', 'jenis', $this->jenis]);

        return $dataProvider;
    }
}
