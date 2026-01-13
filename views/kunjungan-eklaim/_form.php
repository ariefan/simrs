<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaim */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kunjungan-eklaim-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kunjungan_id')->textInput() ?>

    <?= $form->field($model, 'nomor_sep')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kelas_rawat')->textInput() ?>

    <?= $form->field($model, 'adl_sub_acute')->textInput() ?>

    <?= $form->field($model, 'adl_chronic')->textInput() ?>

    <?= $form->field($model, 'icu_indikator')->textInput() ?>

    <?= $form->field($model, 'icu_los')->textInput() ?>

    <?= $form->field($model, 'ventilator_hour')->textInput() ?>

    <?= $form->field($model, 'upgrade_class_ind')->textInput() ?>

    <?= $form->field($model, 'upgrade_class_class')->textInput() ?>

    <?= $form->field($model, 'upgrade_class_los')->textInput() ?>

    <?= $form->field($model, 'add_payment_pct')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birth_weight')->textInput() ?>

    <?= $form->field($model, 'discharge_status')->textInput() ?>

    <?= $form->field($model, 'procedure')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tarif_rs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tarif_poli_eks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_tarif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payor_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payor_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cob_cd')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
