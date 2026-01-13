<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BangsalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bangsal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bangsal-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Bangsal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'bangsal_cd',
            'bangsal_nm',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
