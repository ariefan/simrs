<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jadwal Praktek Dokter per Hari';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <div class="btn-group" role="group">
        <?= Html::a('Manajemen Jadwal', Url::to(['jadwal/index']), ['class' => 'btn btn-info']); ?>
        <?= Html::a('Per Klinik', Url::to(['jadwal/perklinik']), ['class' => 'btn btn-info']); ?>
        <?= Html::a('Per Hari', '#', ['class' => 'btn btn-info disabled']); ?>
    </div><br /><br />

    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th>JENIS PELAYANAN DAN NAMA DOKTER</th>
                <th>SPESIALISASI</th>
                <th>KLINIK</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($datas as $kliniks) { if(count($kliniks) > 0){ 
        ?>
            <tr>
                <th colspan="4" class="info"><?= $kliniks[0]['hari']; ?></th>
            </tr>
        <?php 
            }
            foreach ($kliniks as $data) { 
        ?>
            <tr>
                <td><?= $data['nama_dokter']; ?></td>
                <td><?= $data['nama_spesialis']; ?></td>
                <td><?= $data['nama_klinik']; ?></td>
            </tr>
        <?php
            }}
        ?>
        </tbody>
    </table>
    <?php
        //var_dump($data);exit;
    ?>
</div>
