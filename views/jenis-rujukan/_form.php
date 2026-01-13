<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JenisRujukan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jenis-rujukan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'referensi_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referensi_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reff_tp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'referensi_root')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dr_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modi_datetime')->textInput() ?>

    <?= $form->field($model, 'modi_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info_01')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info_02')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
