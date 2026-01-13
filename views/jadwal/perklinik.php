<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jadwal Praktek Dokter per Klinik';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <div class="btn-group" role="group">
        <?= Html::a('Manajemen Jadwal', Url::to(['jadwal/index']), ['class' => 'btn btn-info']); ?>
        <?= Html::a('Per Klinik', '#', ['class' => 'btn btn-info disabled']); ?>
        <?= Html::a('Per Hari', Url::to(['jadwal/perhari']), ['class' => 'btn btn-info']); ?>
    </div><br /><br />

    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th rowspan="2">JENIS PELAYANAN DAN NAMA DOKTER</th>
                <th rowspan="2">SPESIALISASI</th>
                <th colspan="2">KLINIK BUKA</th>
            </tr>
            <tr>
                <th>HARI</th>
                <th>JAM</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($datas as $kliniks) { if(count($kliniks) > 0){ 
        ?>
            <tr>
                <th colspan="4" class="info"><?= $kliniks[0]['nama_klinik']; ?></th>
            </tr>
        <?php 
            }
            foreach ($kliniks as $data) { 
        ?>
            <tr>
                <td><?= $data['nama_dokter']; ?></td>
                <td><?= $data['nama_spesialis']; ?></td>
                <td><?= $data['hari']; ?></td>
                <td><?= $data['jam']; ?></td>
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
