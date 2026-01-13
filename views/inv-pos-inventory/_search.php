<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosInventorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-pos-inventory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pos_cd') ?>

    <?= $form->field($model, 'pos_nm') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'unit_medis') ?>

    <div class="form-group">
        <?= Html::submitButton('Cari', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
