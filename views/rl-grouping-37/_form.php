<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping37 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rl-grouping37-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rl_ref_37_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'medicalunit_cd')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
