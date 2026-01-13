<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Jadwal;

/**
 * JadwalSearch represents the model behind the search form about `app\models\Jadwal`.
 */
class JadwalSearch extends Jadwal
{
    public $dokter,$poli;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jadwal_id', 'user_id'], 'integer'],
            [['medunit_cd', 'day_tp', 'time_start', 'time_end', 'note','dokter','poli'], 'safe'],
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
        $query = Jadwal::find();

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

        $query->joinWith('user');
        // grid filtering conditions
        $query->andFilterWhere([
            'jadwal_id' => $this->jadwal_id,
            // 'user_id' => $this->user_id,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
        ]);

        $query->andFilterWhere(['like', 'medunit_cd', $this->medunit_cd])
            ->andFilterWhere(['like', 'nama', $this->dokter])
            ->andFilterWhere(['like', 'medunit_nm', $this->poli])
            ->andFilterWhere(['like', 'day_tp', $this->day_tp])
            ->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
