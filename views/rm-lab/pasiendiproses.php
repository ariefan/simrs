<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RmLabSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Hasil Pemeriksaan Laboratorium';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
   Modal::begin([
            'header' => '<h4>Pasien</h4>',
           'options' => [
                'id' => 'kartik-modal',
                'tabindex' => false // important for Select2 to work properly
            ],
        ]);

    echo "<div id='modalContent'></div>";

    Modal::end();

?>


    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

            <div class="portlet-body">
                <div class="actions">
                    <?= Html::a('Kembali', ['index'], ['class'=>'btn btn-circle red modalWindow']) ?>
                </div>

                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th><strong>No. REKAM MEDIS</strong></th>
                            <th><strong>NAMA PASIEN</strong></th>
                            <th><strong>TANGGAL PERIKSA</strong></th>
                            <th><strong>NAMA PENGECEKAN</strong></th>
                            <th><strong>DOKTER PEMERIKSA</strong></th>
                            <th><strong>CATATAN</strong></th>
                            <th><strong>HASIL</strong></th>
                            <th><strong>AKSI</strong></th>
                        </thead>
                        <tbody>
                            <?php foreach($daftarPasien2 as $val): ?>
                                <tr>
                                    <td><?= $val['No Rekam Medis'] ?></td>
                                    <td><?= $val['Nama Pasien'] ?></td>
                                    <td><?= $val['Tanggal Periksa'] ?></td>
                                    <td><?= $val['Nama Pengecekan'] ?></td>
                                    <td><?= $val['Dokter Pemeriksa'] ?></td>
                                    <td><?= $val['Catatan'] ?></td>
                                    <td><?= $val['Hasil'] ?></td>
                                    <td>
                                        <?= 
                                        Html::a('<span class="fa fa-stethoscope"></span> Detail Data', Url::to(['rm-lab/view','id'=>$val['id']]), [
                                                'title' => Yii::t('yii', 'Proses'),
                                                'class' => 'btn btn-circle blue modalWindow',
                                            ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

