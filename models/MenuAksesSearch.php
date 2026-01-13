<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MenuAkses;

/**
 * MenuAksesSearch represents the model behind the search form about `app\models\MenuAkses`.
 */
class MenuAksesSearch extends MenuAkses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_name', 'role_name'], 'safe'],
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
        $query = MenuAkses::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith('menu');
        $query->joinWith('role2');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'menu_nama', $this->menu_name])
            ->andFilterWhere(['like', 'role.name', $this->role_name]);

        return $dataProvider;
    }
}
