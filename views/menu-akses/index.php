<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuAksesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menu Akses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-akses-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Menu Akses', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'menu_name',
                'value'=>'menu.menu_nama'
            ],
            [
                'attribute' => 'role_name',
                'value' => 'role2.name'
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
