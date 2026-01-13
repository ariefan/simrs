<?php 
use yii\helpers\Html;
$this->title = "Hasil Pemeriksaan Laboratorium ".$model->nama." ".$model->rm_id;
?>
<table class="table table-bordered">
    <tbody>
        <tr>
            <th style="width:30%">ID Pemeriksaan</th>
            <td><?= $model->id ?></td>
        </tr>
        <tr>
            <th>No Rekam Medis</th>
            <td><?= $model->rm_id ?></td>
        </tr>
        <tr>
            <th>Kode Lab.</th>
            <td><?= $model->medicalunit_cd ?> ) </td>
        </tr>
    
        <tr>
            <th>Nama Fasilitas</th>
            <td><?= $model->nama ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered">
<tbody>
        <tr>
            <th>id</th>
            <th>rm_id</th>
            <th>medicalunit_cd</th>
            <th>Nama Fasilitas</th>
            <th>Petugas Pemeriksa</th>
        </tr>
        <tr>
            <td><?= $model->id ?></td>
            <td><?= $model->rm_id ?></td>
            <td><?= $model->medicalunit_cd ?></td>
            <td><?= $model->nama ?> C</td>
            <td><?= $model->dokter_nama ?></td>
        </tr>
</tbody>
</table>

            <th>Hasil Pemeriksaan</th>
            <td>
                <ul>
                <?php foreach($hasil as $value): ?>
                    <li><?= "Nama Pasien:".$value['Nama Pasien'] ?></li>
                    <li><?= "TGL. Periksa:".$value['Tanggal Periksa'] ?></li>
                    <li><?= "Unit Pengirim:".$value['Unit Pengirim'] ?></li>
                    <li><?= "Dokter Pengirim:".$value['Dokter Pengirim'] ?></li>
                    <li><?= "Catatan:".$value['Catatan'] ?></li>
                    <li><?= "Hasil:".$value['Hasil'] ?></li>
                    
                <?php endforeach; ?>
                </ul>
            </td>

<table style="padding-top:100px" border="0" align="right" width="35%">

 