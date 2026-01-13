<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\RmLab */

$this->title = 'Laporan Pemeriksaan Laboratorium: '.$model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Pemeriksaan Laboratorium '.$model->nama, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if(Yii::$app->session->getFlash('success')): ?>
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
    <?= Yii::$app->session->getFlash('success'); ?>
    <?= Html::a(', Pasien Berikutnya ',Url::to(['site/index'])) ?>
</div>
<?php endif; ?>

<?php
    Modal::begin([
            'header' => '<h4>Pasien</h4>',
            'id' => 'modal',
        ]);

    echo "<div id='modalContent'></div>";
    Modal::end();
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div>
                    <ul class="pagination">
                        <?php foreach($daftarPasien as $val): ?>
                            <div><?php echo($val['Nama Pasien']); ?>
                            <div><?php echo($val['Tanggal Periksa']); ?>
                            <div><?php echo($val['Unit Pengirim']); ?>
                            <div><?php echo($val['Dokter Pengirim']); ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="rm-lab-view">
    <p>
        <?= Html::a('Ubah Data', ['update', 'id' => $model->id], ['class' => 'btn btn-circle blue modalWindow']) ?>
        <?= Html::a('Kembali', ['pasiendiproses'], ['class' => 'btn btn-circle red modalWindow']) ?>
        <?= Html::a('Unduh', Url::to(['rm-lab/unduh','id'=> $model->id]),['class' => 'btn btn-circle blue btn-sm']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'rm_id',
            'medicalunit_cd',
            'nama',
            'catatan:ntext',
            'hasil:ntext',
            //'dokter',
            'dokter_nama',
        ],
    ]) ?>
</div>
