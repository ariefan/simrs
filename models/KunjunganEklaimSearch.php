<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\KunjunganEklaim;

/**
 * KunjunganEklaimSearch represents the model behind the search form about `app\models\KunjunganEklaim`.
 */
class KunjunganEklaimSearch extends KunjunganEklaim
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kunjungan_id', 'kelas_rawat', 'adl_sub_acute', 'adl_chronic', 'icu_indikator', 'icu_los', 'ventilator_hour', 'upgrade_class_ind', 'upgrade_class_class', 'upgrade_class_los', 'discharge_status'], 'integer'],
            [['nomor_sep', 'add_payment_pct', 'procedure', 'kode_tarif', 'payor_id', 'payor_cd', 'cob_cd'], 'safe'],
            [['birth_weight', 'tarif_rs', 'tarif_poli_eks'], 'number'],
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
        $query = KunjunganEklaim::find();

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
            'kunjungan_id' => $this->kunjungan_id,
            'kelas_rawat' => $this->kelas_rawat,
            'adl_sub_acute' => $this->adl_sub_acute,
            'adl_chronic' => $this->adl_chronic,
            'icu_indikator' => $this->icu_indikator,
            'icu_los' => $this->icu_los,
            'ventilator_hour' => $this->ventilator_hour,
            'upgrade_class_ind' => $this->upgrade_class_ind,
            'upgrade_class_class' => $this->upgrade_class_class,
            'upgrade_class_los' => $this->upgrade_class_los,
            'birth_weight' => $this->birth_weight,
            'discharge_status' => $this->discharge_status,
            'tarif_rs' => $this->tarif_rs,
            'tarif_poli_eks' => $this->tarif_poli_eks,
        ]);

        $query->andFilterWhere(['like', 'nomor_sep', $this->nomor_sep])
            ->andFilterWhere(['like', 'add_payment_pct', $this->add_payment_pct])
            ->andFilterWhere(['like', 'procedure', $this->procedure])
            ->andFilterWhere(['like', 'kode_tarif', $this->kode_tarif])
            ->andFilterWhere(['like', 'payor_id', $this->payor_id])
            ->andFilterWhere(['like', 'payor_cd', $this->payor_cd])
            ->andFilterWhere(['like', 'cob_cd', $this->cob_cd]);

        return $dataProvider;
    }
}
