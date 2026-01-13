<?php 
use yii\helpers\Html;
$this->title = "Kartu Pasien ".$model->nama." ".$model->mr;
?>
<table class="table table-bordered">
    <tbody>
        <tr>
            <th style="width:30%">No. Pasien</th>
            <td><?= $model->mr ?></td>
        </tr>
        <tr>
            <th>Nama Pasien</th>
            <td><?= $model->nama ?></td>
        </tr>
        <tr>
            <th>Tanggal Lahir</th>
            <td><?= $model->tanggal_lahir ?> </td>
        </tr>
    
        <tr>
            <th>Alamat</th>
            <td><?= $model->alamat ?></td>
        </tr>
    </tbody>
</table>

<table style="padding-top:100px" border="0" align="right" width="35%">

 