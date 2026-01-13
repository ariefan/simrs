<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\TarifTpItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-tp-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tariftp_no') ?>

    <?= $form->field($model, 'seq_no') ?>

    <?= $form->field($model, 'item_nm') ?>

    <?= $form->field($model, 'tarif_tp') ?>

    <?= $form->field($model, 'trx_tarif_seqno') ?>

    <?php // echo $form->field($model, 'tarif_item') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'modi_id') ?>

    <?php // echo $form->field($model, 'modi_datetime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
