<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-item-type-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= Html::activeHiddenInput($model,'modi_id',['value'=>Yii::$app->user->id]); ?>
    <?= Html::activeHiddenInput($model,'modi_datetime',['value'=>date('Y-m-d H:i:s')]); ?>

    <?= $form->field($model, 'type_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_nm')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
