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
                <p>
                <?= Html::a('Kembali', ['index'], ['class' => 'btn btn-primary']) ?>
                </p>

                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th>No Rekam Medis</th>
                            <th>Nama Pasien</th>
                            <th>Tanggal Periksa</th>
                            <th>Nama Pengecekan</th>
                            <th>Dokter Pemeriksa</th>
                            <th>Catatan</th>
                            <th>Hasil</th>
                            <th>Aksi</th>
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
                                        Html::a('<span class="fa fa-stethoscope"></span> Ubah Data', Url::to(['rm-rad/update','id'=>$val['id']]), [
                                                'title' => Yii::t('yii', 'Proses'),
                                                'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                                            ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

