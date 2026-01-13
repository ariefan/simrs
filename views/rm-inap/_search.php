<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\RmInapSeach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rm-inap-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'rm_id') ?>

    <?= $form->field($model, 'anamnesis') ?>

    <?= $form->field($model, 'pemeriksaan_fisik') ?>

    <?= $form->field($model, 'assesment') ?>

    <?php // echo $form->field($model, 'plan') ?>

    <?php // echo $form->field($model, 'created') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
