<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AccountGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-group-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'accgroup_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accgroup_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_no')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
