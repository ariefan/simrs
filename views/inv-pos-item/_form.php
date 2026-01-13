<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-pos-item-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'quantity')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <label>Catatan</label>
        <input type="text" class="form-control" placeholder="Catatan Mengenai Penyesuaian Stok..." name="catatan">
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
