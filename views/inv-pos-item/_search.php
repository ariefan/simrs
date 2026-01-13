<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-pos-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pos_cd') ?>

    <?= $form->field($model, 'item_cd') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'modi_id') ?>

    <?= $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
