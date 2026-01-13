<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UnitMedisItem;

/**
 * UnitMedisItemSearch represents the model behind the search form about `app\models\UnitMedisItem`.
 */
class UnitMedisItemSearch extends UnitMedisItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['medicalunit_cd', 'medunit_cd', 'medicalunit_root', 'medicalunit_nm', 'root_st', 'file_format', 'modi_id', 'modi_datetime'], 'safe'],
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
        $query = UnitMedisItem::find();

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

        $query->andFilterWhere(['like', 'medicalunit_cd', $this->medicalunit_cd])
            ->andFilterWhere(['like', 'medunit_cd', $this->medunit_cd])
            ->andFilterWhere(['like', 'medicalunit_root', $this->medicalunit_root])
            ->andFilterWhere(['like', 'medicalunit_nm', $this->medicalunit_nm])
            ->andFilterWhere(['like', 'root_st', $this->root_st])
            ->andFilterWhere(['like', 'file_format', $this->file_format])
            ->andFilterWhere(['like', 'modi_id', $this->modi_id]);

        return $dataProvider;
    }
}
