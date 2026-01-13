<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaimSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kunjungan-eklaim-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'kunjungan_id') ?>

    <?= $form->field($model, 'nomor_sep') ?>

    <?= $form->field($model, 'kelas_rawat') ?>

    <?= $form->field($model, 'adl_sub_acute') ?>

    <?= $form->field($model, 'adl_chronic') ?>

    <?php // echo $form->field($model, 'icu_indikator') ?>

    <?php // echo $form->field($model, 'icu_los') ?>

    <?php // echo $form->field($model, 'ventilator_hour') ?>

    <?php // echo $form->field($model, 'upgrade_class_ind') ?>

    <?php // echo $form->field($model, 'upgrade_class_class') ?>

    <?php // echo $form->field($model, 'upgrade_class_los') ?>

    <?php // echo $form->field($model, 'add_payment_pct') ?>

    <?php // echo $form->field($model, 'birth_weight') ?>

    <?php // echo $form->field($model, 'discharge_status') ?>

    <?php // echo $form->field($model, 'procedure') ?>

    <?php // echo $form->field($model, 'tarif_rs') ?>

    <?php // echo $form->field($model, 'tarif_poli_eks') ?>

    <?php // echo $form->field($model, 'kode_tarif') ?>

    <?php // echo $form->field($model, 'payor_id') ?>

    <?php // echo $form->field($model, 'payor_cd') ?>

    <?php // echo $form->field($model, 'cob_cd') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
