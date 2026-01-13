<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manajemen Jadwal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <div class="btn-group" role="group">
        <?= Html::a('Manajemen Jadwal', '#', ['class' => 'btn btn-info disabled']); ?>
        <?= Html::a('Rekap Jadwal', Url::to(['jadwal/rekap']), ['class' => 'btn btn-info']); ?>
    </div><br /><br />

    <p>
        <?= Html::a('Tambah Jadwal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'jadwal_id',
            [
                'attribute' => 'dokter',
                'value' => 'user.nama'
            ],
            [
                'attribute' => 'poli',
                'value' => 'medunitCd.medunit_nm'
            ],
            // 'medunitCd.medunit_nm',
            'day_tp',
            'time_start',
            'time_end',
            // 'note:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
