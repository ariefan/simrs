<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KlinikSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="klinik-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'klinik_id') ?>

    <?= $form->field($model, 'klinik_nama') ?>

    <?= $form->field($model, 'region_cd') ?>

    <?= $form->field($model, 'kode_pos') ?>

    <?= $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'nomor_telp_1') ?>

    <?php // echo $form->field($model, 'nomor_telp_2') ?>

    <?php // echo $form->field($model, 'kepala_klinik') ?>

    <?php // echo $form->field($model, 'maximum_row') ?>

    <?php // echo $form->field($model, 'luas_tanah') ?>

    <?php // echo $form->field($model, 'luas_bangunan') ?>

    <?php // echo $form->field($model, 'izin_no') ?>

    <?php // echo $form->field($model, 'izin_tgl') ?>

    <?php // echo $form->field($model, 'izin_oleh') ?>

    <?php // echo $form->field($model, 'izin_sifat') ?>

    <?php // echo $form->field($model, 'izin_masa_berlaku') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
