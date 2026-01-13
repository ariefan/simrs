<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RmLabSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rm-lab-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'rm_id') ?>

    <?= $form->field($model, 'medicalunit_cd') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'catatan') ?>

    <?php // echo $form->field($model, 'hasil') ?>

    <?php // echo $form->field($model, 'dokter') ?>

    <?php // echo $form->field($model, 'dokter_nama') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
