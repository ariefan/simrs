<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveField;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use app\models\JenisPeriksaLab;
use app\models\RmLabNapzaDetail;
/* @var $this yii\web\View */
/* @var $model app\models\RmLabNapza */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rm-lab-napza-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nomor_surat')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'tanggal_surat')->widget(
                DatePicker::className(), [
                     'value' => date('Y-m-d'),
                     // 'inline' => true,
                     'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'permintaan')->textInput(['maxlength' => true]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'keperluan')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-md-12">

        <?php if (!$model->isNewRecord){
            $model->hasils = array_column(RmLabNapzaDetail::find()->select('periksa_id')->where(['lab_napza_id'=>$model->lab_napza_id,'hasil'=>'1'])->asArray()->all(),'periksa_id');
            } ?>
            <?= $form->field($model, 'hasils')->checkboxList(ArrayHelper::map(JenisPeriksaLab::find()->asArray()->all(), 'periksa_id', 'periksa_nama')) ?>
            <p style="color: red">*catatan: Centang untuk menandai hasil pemeriksaan sebagai positif.</p>
        </div>


        
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Simpan' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
