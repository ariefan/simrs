<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMoveSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-item-move-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'pos_cd') ?>

    <?= $form->field($model, 'pos_destination') ?>

    <?= $form->field($model, 'item_cd') ?>

    <?= $form->field($model, 'trx_by') ?>

    <?php // echo $form->field($model, 'trx_datetime') ?>

    <?php // echo $form->field($model, 'trx_qty') ?>

    <?php // echo $form->field($model, 'old_stock') ?>

    <?php // echo $form->field($model, 'new_stock') ?>

    <?php // echo $form->field($model, 'purpose') ?>

    <?php // echo $form->field($model, 'vendor') ?>

    <?php // echo $form->field($model, 'move_tp') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
