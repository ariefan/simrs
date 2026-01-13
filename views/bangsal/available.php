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


    <table class="table table-condensed table-bordered">
        <thead>
            <tr>
                <th>NAMA BANGSAL</th>
                <th>KELAS</th>
                <th>RUANG</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($datas as $kelases) { if(count($kelases) > 0){ 
        ?>
            <tr>
                <td style="min-width: 200px;"><?= $kelases[0]['bangsal_nm']; ?></td>
                <td style="min-width: 100px;"><?= $kelases[0]['kelas_nm']; ?></td>
                <td>
        <?php 
            foreach ($kelases as $data) {     
                $status = $data['status'] == 1 ? 'success' : 'danger';
                echo '<span class="label label-'.$status.'">'.$data['ruang_nm'].'</span> ';
            } 
        ?>
                </td>
            </tr>
        <?php }} ?>
        </tbody>
    </table>
</div>

<?php
$this->registerCss("
    .label {
        font-family: Helvetica,Arial,sans-serif; 
        font-size: 75%;
    }

    .label-danger { background-color: #d9534f; }
    .label-success { background-color: #5cb85c; }
");
?>