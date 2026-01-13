<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Obat */

$this->title = $model->nama_merk;
$this->params['breadcrumbs'][] = ['label' => 'Obat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <table class="table table-bordered">
        <thead>
            <th>Tanggal Stok</th>
            <th>Tipe</th>
            <th>Jenis</th>
            <th>Jumlah</th>
            <th>Keterangan</th>
            <th>Stok Sebelum</th>
        </thead>
        <tbody>
            <?php foreach($stok as $value): ?>
            <td><?= $value['tanggal_stok'] ?></td>
            <td><?= $value['tipe'] ?></td>
            <td><?= $value['jenis'] ?></td>
            <td><?= $value['jumlah'] ?></td>
            <td><?= $value['keterangan'] ?></td>
            <td><?= $value['stok_sebelum'] ?></td>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
