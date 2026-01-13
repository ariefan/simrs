<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JadwalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rekap Jadwal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jadwal-index">

    <div class="btn-group" role="group">
        <?= Html::a('Manajemen Jadwal', Url::to(['jadwal/index']), ['class' => 'btn btn-info']); ?>
        <?= Html::a('Rekap Jadwal', '#', ['class' => 'btn btn-info disabled']); ?>
    </div><br /><br />

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Senin</th>
                <th>Selasa</th>
                <th>Rabu</th>
                <th>Kamis</th>
                <th>Jumat</th>
                <th>Sabtu</th>
                <th>Minggu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $poli=>$v1): ?>
            <?php foreach($v1 as $dokter=>$v2): ?>
            <tr>
                <td><?= $poli ?></td>
                <td><?= $dokter ?></td>
                <td><?= isset($data[$poli][$dokter]['Senin']) ? implode('<br/> ', $data[$poli][$dokter]['Senin']) : '' ?></td>
                <td><?= isset($data[$poli][$dokter]['Selasa']) ? implode('<br/> ', $data[$poli][$dokter]['Selasa']) : '' ?></td>
                <td><?= isset($data[$poli][$dokter]['Rabu']) ? implode('<br/> ', $data[$poli][$dokter]['Rabu']) : '' ?></td>
                <td><?= isset($data[$poli][$dokter]['Kamis']) ? implode('<br/> ', $data[$poli][$dokter]['Kamis']) : '' ?></td>
                <td><?= isset($data[$poli][$dokter]['Jumat']) ? implode('<br/> ', $data[$poli][$dokter]['Jumat']) : '' ?></td>
                <td><?= isset($data[$poli][$dokter]['Sabtu']) ? implode('<br/> ', $data[$poli][$dokter]['Sabtu']) : '' ?></td>
                <td><?= isset($data[$poli][$dokter]['Minggu']) ? implode('<br/> ', $data[$poli][$dokter]['Minggu']) : '' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>
