<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Sensus Rawat Inap';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="instruksi-index">

    <?php $form = ActiveForm::begin(["id"=>"form-pasien"]); ?>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal Awal</label>
            <input type="date" class="form-control" name="tgl_awal" value="<?= date('Y') ?>-01-01">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
            <label>Tanggal Akhir</label>
            <input type="date" class="form-control" name="tgl_akhir" value="<?= date('Y') ?>-12-31">
        </div>
      </div>

    </div>

    <div class="form-group">
        <?= Html::submitButton('Lihat', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
