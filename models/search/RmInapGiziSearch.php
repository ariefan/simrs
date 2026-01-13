<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RmInapGizi;

/**
 * RmInapGiziSeach represents the model behind the search form about `app\models\RmInapGizi`.
 */
class RmInapGiziSearch extends RmInapGizi
{
    /**
     * @inheritdoc
     */
    // public $mr_id;
    public function rules()
    {
        return [
            [['id', 'rm_id'], 'integer'],
            [['jam_makan','jam_makan_spesifik' ,'created','status','kode_diet','diagnosa'], 'safe'],
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
        $query = RmInapGizi::find();

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
        $query->joinWith('mr');
        $query->joinWith('ruang');
       // $query->joinWith('rekamMedis');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'rekam_medis.rm_id' => $this->rm_id,
            'created' => $this->created,
        ]);

        $query->andFilterWhere(['like', 'jam_makan', $this->jam_makan])
            ->andFilterWhere(['like', 'jam_makan_spesifik', $this->status])
            ->andFilterWhere(['like', 'gizi_diet.nama_diet', $this->kode_diet])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'diagnosa', $this->diagnosa])
            ->andFilterWhere(['like', 'rekamMedis.mr', $this->mr_id]);
            

        return $dataProvider;
    }
}
