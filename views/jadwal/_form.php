<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Jadwal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jadwal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(app\models\Dokter::find()->all(), 'user_id', 'nama'),
        'options' => ['placeholder' => '-- Pilih Dokter --'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>


    <?= $form->field($model, 'medunit_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(app\models\UnitMedis::find()->all(), 'medunit_cd', 'medunit_nm'),
        'options' => ['placeholder' => '-- Pilih Unit Medis --'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'day_tp')->dropDownList([ 'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu', 'Minggu' => 'Minggu', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'time_start')->widget(TimePicker::classname(), [
		'pluginOptions' => [
			'showSeconds' => false,
			'showMeridian' => false,
		]]) 
	?>

    <?= $form->field($model, 'time_end')->widget(TimePicker::classname(), [
		'pluginOptions' => [
			'showSeconds' => false,
			'showMeridian' => false,
		]]) 
	?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
