<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tindakan;

/**
 * TindakanSearch represents the model behind the search form about `app\models\Tindakan`.
 */
class TindakanSearch extends Tindakan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tindakan_cd', 'tindakan_root', 'nama_tindakan', 'created', 'modified'], 'safe'],
            [['klinik_id', 'total_tarif', 'biaya_wajib'], 'integer'],
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
        $query = Tindakan::find();

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
            'klinik_id' => $this->klinik_id,
            'total_tarif' => $this->total_tarif,
            'created' => $this->created,
            'modified' => $this->modified,
            'biaya_wajib' => $this->biaya_wajib,
        ]);

        $query->andFilterWhere(['like', 'tindakan_cd', $this->tindakan_cd])
            ->andFilterWhere(['like', 'tindakan_root', $this->tindakan_root])
            ->andFilterWhere(['like', 'nama_tindakan', $this->nama_tindakan]);

        return $dataProvider;
    }
}
