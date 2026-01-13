<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TarifParamedisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarif Paramedis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-paramedis-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Tarif Paramedis', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tarif_paramedis_id',
            'insurance_cd',
            'kelas_cd',
            'specialis_cd',
            'paramedis_tp',
            // 'tarif',
            // 'account_cd',
            // 'modi_id',
            // 'modi_datetime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
