<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\AccountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'account_cd') ?>

    <?= $form->field($model, 'accgroup_cd') ?>

    <?= $form->field($model, 'account_nm') ?>

    <?= $form->field($model, 'default_amount') ?>

    <?= $form->field($model, 'order_no') ?>

    <?php // echo $form->field($model, 'print_single_st') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
