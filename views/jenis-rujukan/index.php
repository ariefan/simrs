<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JenisRujukanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jenis Rujukans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-rujukan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Jenis Rujukan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'referensi_cd',
            'referensi_nm',
            'reff_tp',
            'referensi_root',
            'dr_nm',
            // 'address:ntext',
            // 'phone',
            // 'modi_datetime',
            // 'modi_id',
            // 'info_01',
            // 'info_02',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
