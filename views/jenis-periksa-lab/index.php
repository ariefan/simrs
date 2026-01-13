<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JenisPeriksaLabSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Periksa Labs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-periksa-lab-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jenis Periksa Lab', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'periksa_id',
            'periksa_nama',
            'periksa_group',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
