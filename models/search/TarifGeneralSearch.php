<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TarifGeneral;

/**
 * TarifGeneralSearch represents the model behind the search form about `app\models\TarifGeneral`.
 */
class TarifGeneralSearch extends TarifGeneral
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarif_general_id'], 'integer'],
            [['tarif_nm', 'insurance_cd', 'kelas_cd', 'auto_add', 'medical_tp', 'account_cd', 'modi_id', 'modi_datetime'], 'safe'],
            [['tarif'], 'number'],
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
        $query = TarifGeneral::find();

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
            'tarif_general_id' => $this->tarif_general_id,
            'tarif' => $this->tarif,
            'modi_datetime' => $this->modi_datetime,
        ]);

        $query->andFilterWhere(['like', 'tarif_nm', $this->tarif_nm])
            ->andFilterWhere(['like', 'insurance_cd', $this->insurance_cd])
            ->andFilterWhere(['like', 'kelas_cd', $this->kelas_cd])
            ->andFilterWhere(['like', 'auto_add', $this->auto_add])
            ->andFilterWhere(['like', 'medical_tp', $this->medical_tp])
            ->andFilterWhere(['like', 'account_cd', $this->account_cd])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
