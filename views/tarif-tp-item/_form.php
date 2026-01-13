<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TarifTpItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-tp-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tariftp_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seq_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_nm')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tarif_tp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trx_tarif_seqno')->textInput() ?>

    <?= $form->field($model, 'tarif_item')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
