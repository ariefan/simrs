<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JenisKunjungan;

/**
 * JenisKunjunganSearch represents the model behind the search form about `app\models\JenisKunjungan`.
 */
class JenisKunjunganSearch extends JenisKunjungan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jns_kunjungan_id'], 'integer'],
            [['jns_kunjungan_nama'], 'safe'],
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
        $query = JenisKunjungan::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'jns_kunjungan_id' => $this->jns_kunjungan_id,
        ]);

        $query->andFilterWhere(['like', 'jns_kunjungan_nama', $this->jns_kunjungan_nama]);

        return $dataProvider;
    }
}
