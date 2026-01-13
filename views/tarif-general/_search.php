<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TarifGeneralSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-general-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tarif_general_id') ?>

    <?= $form->field($model, 'tarif_nm') ?>

    <?= $form->field($model, 'insurance_cd') ?>

    <?= $form->field($model, 'kelas_cd') ?>

    <?= $form->field($model, 'tarif') ?>

    <?php // echo $form->field($model, 'auto_add') ?>

    <?php // echo $form->field($model, 'medical_tp') ?>

    <?php // echo $form->field($model, 'account_cd') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
