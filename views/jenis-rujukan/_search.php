<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JenisRujukanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jenis-rujukan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'referensi_cd') ?>

    <?= $form->field($model, 'referensi_nm') ?>

    <?= $form->field($model, 'reff_tp') ?>

    <?= $form->field($model, 'referensi_root') ?>

    <?= $form->field($model, 'dr_nm') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'info_01') ?>

    <?php // echo $form->field($model, 'info_02') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
