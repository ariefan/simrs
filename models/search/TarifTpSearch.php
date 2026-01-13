<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TarifTp;

/**
 * TarifTpSearch represents the model behind the search form about `app\models\TarifTp`.
 */
class TarifTpSearch extends TarifTp
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tariftp_no', 'tarif_seqno'], 'integer'],
            [['tariftp_nm', 'insurance_cd', 'kelas_cd', 'modi_id', 'tarif_tp', 'modi_datetime'], 'safe'],
            [['tarif_total'], 'number'],
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
        $query = TarifTp::find();

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
            'tariftp_no' => $this->tariftp_no,
            'tarif_total' => $this->tarif_total,
            'tarif_seqno' => $this->tarif_seqno,
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->andFilterWhere(['like', 'tariftp_nm', $this->tariftp_nm])
            ->andFilterWhere(['like', 'insurance_cd', $this->insurance_cd])
            ->andFilterWhere(['like', 'kelas_cd', $this->kelas_cd])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id])
            ->andFilterWhere(['like', 'tarif_tp', $this->tarif_tp]);

        return $dataProvider;
    }
}
