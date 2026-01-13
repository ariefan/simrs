<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnitMedisItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-medis-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'medicalunit_cd') ?>

    <?= $form->field($model, 'medunit_cd') ?>

    <?= $form->field($model, 'medicalunit_root') ?>

    <?= $form->field($model, 'medicalunit_nm') ?>

    <?= $form->field($model, 'root_st') ?>

    <?php // echo $form->field($model, 'file_format') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
