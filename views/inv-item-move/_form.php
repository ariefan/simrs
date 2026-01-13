<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\InvItemMove */
/* @var $batch app\models\InvBatchItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-item-move-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($batch, 'batch_no')->textInput(['value' => date('Ymdhis')]) ?>
    <?= $form->field($batch, 'buy_price')->textInput(['type' => 'number']) ?>
    <?= $form->field($batch, 'sell_price')->textInput(['type' => 'number']) ?> 
    <?= $form->field($batch, 'expire_date')->widget(DateTimePicker::classname(), [
		'pluginOptions' => [
			'showSeconds' => false,
			'showMeridian' => false,
		]]) 
	?>
    <?= $form->field($model, 'pos_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(app\models\InvPosInventory::find()->all(), 'pos_cd', 'pos_nm'),
        'options' => ['placeholder' => '-- Pilih Pos --'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'pos_destination')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(app\models\InvPosInventory::find()->all(), 'pos_cd', 'pos_nm'),
        'options' => ['placeholder' => '-- Pilih Pos --'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'item_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(app\models\InvItemMaster::findBySql("SELECT item_cd,CONCAT(item_cd,' - ',item_nm) AS item_nm FROM inv_item_master")->asArray()->all(), 'item_cd', 'item_nm'),
        'options' => ['placeholder' => '-- Pilih Item --'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'trx_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trx_datetime')->widget(DateTimePicker::classname(), [
		'pluginOptions' => [
			'showSeconds' => false,
			'showMeridian' => false,
		]]) 
	?>

    <?= $form->field($model, 'trx_qty')->textInput(['type' => 'number', 'maxlength' => true]) ?>

    <?= $form->field($model, 'old_stock')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'new_stock')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purpose')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vendor')->textInput(['maxlength' => true]) ?>

    <!--<?= $form->field($model, 'move_tp')->dropDownList([ 'In' => 'In', 'Out' => 'Out', 'Transfer' => 'Transfer', 'Konversi' => 'Konversi', 'Penyesuaian' => 'Penyesuaian', ], ['prompt' => '']) ?>-->

    <?= $form->field($model, 'catatan')->textinput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
