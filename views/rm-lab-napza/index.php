<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RmLabNapzaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rm Lab Napzas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-lab-napza-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'lab_napza_id',
            'rm_id',
            'nomor_surat',
            'tanggal_surat',
            'permintaan',
            // 'keperluan:ntext',
            // 'tanggal_periksa',
            // 'created',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
