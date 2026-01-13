<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Konfigurasi;

/**
 * KonfigurasiSearch represents the model behind the search form about `app\models\Konfigurasi`.
 */
class KonfigurasiSearch extends Konfigurasi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KONF_KODE', 'KONF_NILAI', 'KONF_KETERANGAN'], 'safe'],
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
        $query = Konfigurasi::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'KONF_KODE', $this->KONF_KODE])
            ->andFilterWhere(['like', 'KONF_NILAI', $this->KONF_NILAI])
            ->andFilterWhere(['like', 'KONF_KETERANGAN', $this->KONF_KETERANGAN]);

        return $dataProvider;
    }
}
