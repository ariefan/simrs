<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JenisRujukan;

/**
 * JenisRujukanSearch represents the model behind the search form about `app\models\JenisRujukan`.
 */
class JenisRujukanSearch extends JenisRujukan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referensi_cd', 'referensi_nm', 'reff_tp', 'referensi_root', 'dr_nm', 'address', 'phone', 'modi_datetime', 'modi_id', 'info_01', 'info_02'], 'safe'],
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
        $query = JenisRujukan::find();

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
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->andFilterWhere(['like', 'referensi_cd', $this->referensi_cd])
            ->andFilterWhere(['like', 'referensi_nm', $this->referensi_nm])
            ->andFilterWhere(['like', 'reff_tp', $this->reff_tp])
            ->andFilterWhere(['like', 'referensi_root', $this->referensi_root])
            ->andFilterWhere(['like', 'dr_nm', $this->dr_nm])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id])
            ->andFilterWhere(['like', 'info_01', $this->info_01])
            ->andFilterWhere(['like', 'info_02', $this->info_02]);

        return $dataProvider;
    }
}
