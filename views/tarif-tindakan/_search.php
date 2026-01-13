<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TarifTindakanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-tindakan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tarif_tindakan_id') ?>

    <?= $form->field($model, 'insurance_cd') ?>

    <?= $form->field($model, 'kelas_cd') ?>

    <?= $form->field($model, 'treatment_cd') ?>

    <?= $form->field($model, 'tarif') ?>

    <?php // echo $form->field($model, 'account_cd') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
