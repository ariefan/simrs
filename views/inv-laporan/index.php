<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\InvItemMaster;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\InvItemMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$jenis_laporan = ['Mutasi', 'Supplier'];

$this->title = 'Laporan Inventori';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-master-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php $form = ActiveForm::begin(['action' => 'cetak']); ?>
    <div class="form-group">
        <label>Tanggal</label>
        <input type="date" value="<?= date('Y-m-d') ?>" class="form-control" name="tanggal_awal" placeholder="Tanggal"> s/d
        <input type="date" value="<?= date('Y-m-d') ?>" class="form-control" name="tanggal_akhir" placeholder="Tanggal">
    </div>
    

    <div class="form-group">
        <label>Jenis Laporan</label>
        <?=
        Select2::widget([
            'name' => 'jenis_laporan',
            'value' => '',
            'data' => $jenis_laporan,
            'options' => ['placeholder' => 'Pilih Laporan ...']
        ]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
