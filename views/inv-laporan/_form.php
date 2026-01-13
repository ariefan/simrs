<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Asuransi;
use app\models\Kelas;
use app\models\Account;
use app\models\InvItemMaster;
use app\models\InvItemType;
use app\models\InvUnit;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-item-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_cd')->textInput(['maxlength' => true, 'readonly' => 'readonly']) ?>

    <?= $form->field($model, 'type_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(InvItemType::find()->select(['type_cd', 'type_nm'])->asArray()->all(), 'type_cd', 'type_nm'),
        'options' => ['placeholder' => 'TYPE'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'unit_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(InvUnit::find()->select(['unit_cd', 'unit_nm'])->asArray()->all(), 'unit_cd', 'unit_nm'),
        'options' => ['placeholder' => 'UNIT'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'item_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'barcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_price_buy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vat_tp')->widget(Select2::classname(), [
        'data' =>  ["NO VAT"=>"NO VAT","INCLUDE"=>"INCLUDE",'EXCLUDE'=>'EXCLUDE'],
        'options' => ['placeholder' => 'VAT'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'ppn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reorder_point')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'minimum_stock')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'maximum_stock')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'generic_st')->widget(Select2::classname(), [
        'data' =>  [0=>"TIDAK",1=>"YA"],
        'options' => ['placeholder' => 'Generic'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'active_st')->widget(Select2::classname(), [
        'data' =>  [0=>"TIDAK",1=>"YA"],
        'options' => ['placeholder' => 'AKTIF'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'inventory_st')->widget(Select2::classname(), [
        'data' =>  [0=>"TIDAK",1=>"YA"],
        'options' => ['placeholder' => 'INVENTORI'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'tariftp_cd')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
