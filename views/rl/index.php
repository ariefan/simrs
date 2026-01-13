<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;

$this->title = 'RL';
?>
<div class="site-index">
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label>Tahun</label>
        <input type="number" value="<?= date('Y') ?>" class="form-control" name="tahun" placeholder="Isi dengan Tahun">
    </div>

    <div class="form-group">
        <label>Tanggal Awal</label>
        <?= DatePicker::widget([
            'name' => 'start_date',
            'value' => date('Y').'-01-01',
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]);?>
    </div>
    <div class="form-group">
        <label>Tanggal Akhir</label>
        <?= DatePicker::widget([
            'name' => 'end_date',
            'value' => date('Y').'-12-31',
            'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
        ]);?>
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
