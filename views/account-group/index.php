<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Account Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-group-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Account Group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'accgroup_cd',
            'accgroup_nm',
            'order_no',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
