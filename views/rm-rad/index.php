<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RmLabSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pemeriksaan Radiologi';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="rm-lab-index">
    <?php $form = ActiveForm::begin(); ?>
    <input type="date" name="tanggal" value="<?= $tanggal ?>">
    <?= Html::submitButton('Lihat', ['class' => 'btn btn-circle green']) ?>

    <?php ActiveForm::end(); ?>

            <div class="portlet-body">
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th>No Rekam Medis</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Periksa</th>
                            <th>Unit Pengirim</th>
                            <th>Dokter Pengirim</th>
                            <th>Pemeriksaan</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach($data as $val): ?>
                                <tr>
                                    <td><?= $val['mr'] ?></td>
                                    <td><?= $val['nama'] ?></td>
                                    <td><?= $val['tanggal_periksa'] ?></td>
                                    <td><?= $val['poli_pengirimi'] ?></td>
                                    <td><?= $val['dokter_pengirim'] ?></td>
                                    <td><?= $val['pemeriksaan'] ?></td>
                                    
                                    <td>
                                        <?php 
                                        if(empty($val['catatan'])) {
                                            echo Html::a('<span class="fa fa-stethoscope"></span> Proses', Url::to(['rm-rad/create','id'=>$val['kunjungan_id']]), [
                                                'title' => Yii::t('yii', 'Proses'),
                                                'class' => 'btn red btn-sm btn-outline sbold uppercase',
                                            ]);
                                        } else {
                                            echo Html::a('<span class="fa fa-stethoscope"></span> Update', Url::to(['rm-rad/create','id'=>$val['kunjungan_id']]), [
                                                'title' => Yii::t('yii', 'Update'),
                                                'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                                            ]);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

</div>
