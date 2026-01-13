<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RefKokab */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-kokab-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kota_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kokab_nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'provinsi_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
