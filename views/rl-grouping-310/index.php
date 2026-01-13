<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RlGrouping310Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rl Grouping310s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping310-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Rl Grouping310', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'rl_ref_310_no',
            'tindakan_cd',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
