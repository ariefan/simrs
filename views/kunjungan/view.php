<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Kunjungan */

$this->title = 'Nomor RM : '.$model->mr;
$this->params['breadcrumbs'][] = ['label' => 'Kunjungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunjungan-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mr',
            'tipe_kunjungan',
            'tanggal_periksa',
            'jam_masuk',
            'jam_selesai',
            'unit.medunit_nm',
            'jenis.jns_kunjungan_nama',
            'cara.cara_nama',
            'asal.asal_nama',
            'jenis_keluar',
            'status',
            'created',
            'user_input',
        ],
    ]) ?>

</div>
