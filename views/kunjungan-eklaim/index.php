<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganEklaimSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kunjungan Eklaims';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunjungan-eklaim-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Kunjungan Eklaim', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'kunjungan_id',
            'nomor_sep',
            'kelas_rawat',
            'adl_sub_acute',
            'adl_chronic',
            // 'icu_indikator',
            // 'icu_los',
            // 'ventilator_hour',
            // 'upgrade_class_ind',
            // 'upgrade_class_class',
            // 'upgrade_class_los',
            // 'add_payment_pct',
            // 'birth_weight',
            // 'discharge_status',
            // 'procedure:ntext',
            // 'tarif_rs',
            // 'tarif_poli_eks',
            // 'kode_tarif',
            // 'payor_id',
            // 'payor_cd',
            // 'cob_cd',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
