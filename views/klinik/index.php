<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KlinikSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Klinik';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="klinik-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'klinik_id',
            'klinik_nama',
            'region_cd',
            'kode_pos',
            'fax',
            // 'email:email',
            // 'website',
            // 'alamat:ntext',
            // 'nomor_telp_1',
            // 'nomor_telp_2',
            // 'kepala_klinik',
            // 'maximum_row',
            // 'luas_tanah',
            // 'luas_bangunan',
            // 'izin_no',
            // 'izin_tgl',
            // 'izin_oleh',
            // 'izin_sifat',
            // 'izin_masa_berlaku',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
