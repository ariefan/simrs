<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnitMedis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-medis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'medunit_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'medunit_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'medunit_tp')->dropDownList([ 'Poliklinik' => 'Poliklinik', 'Laboratorium' => 'Laboratorium', 'Radiologi' => 'Radiologi', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Simpan Data Baru' : 'Simpan Perubahan Data', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
