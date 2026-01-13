<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\InvItemMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-item-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'item_cd') ?>

    <?= $form->field($model, 'type_cd') ?>

    <?= $form->field($model, 'unit_cd') ?>

    <?= $form->field($model, 'item_nm') ?>

    <?= $form->field($model, 'barcode') ?>

    <?php // echo $form->field($model, 'currency_cd') ?>

    <?php // echo $form->field($model, 'item_price_buy') ?>

    <?php // echo $form->field($model, 'item_price') ?>

    <?php // echo $form->field($model, 'vat_tp') ?>

    <?php // echo $form->field($model, 'ppn') ?>

    <?php // echo $form->field($model, 'reorder_point') ?>

    <?php // echo $form->field($model, 'minimum_stock') ?>

    <?php // echo $form->field($model, 'maximum_stock') ?>

    <?php // echo $form->field($model, 'generic_st') ?>

    <?php // echo $form->field($model, 'active_st') ?>

    <?php // echo $form->field($model, 'inventory_st') ?>

    <?php // echo $form->field($model, 'tariftp_cd') ?>

    <?php // echo $form->field($model, 'last_user') ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
