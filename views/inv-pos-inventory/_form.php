<?php

use app\models\InvUnit;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosInventory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-pos-inventory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pos_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pos_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
    $bangsal=(new \yii\db\Query())
        ->select(['bangsal_cd as cd','bangsal_nm as nm'])
        ->from('bangsal')
        ->createCommand()
        ->sql;
    $unit_medis=(new \yii\db\Query())
        ->select(['medunit_cd as cd','medunit_nm as nm'])
        ->from('unit_medis')
        ->union($bangsal)
        ->all();
    ?>
    <?= $form->field($model, 'unit_medis')->widget(Select2::classname(),[
        'data'=>ArrayHelper::map($unit_medis,'cd','nm'),
        'value'=>$model->unit_medis
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
