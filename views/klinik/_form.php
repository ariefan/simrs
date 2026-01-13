<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Region;
use app\models\Dokter;

/* @var $this yii\web\View */
/* @var $model app\models\Klinik */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="klinik-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'klinik_nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_cd')->dropDownList(
        ArrayHelper::map(Region::find()->all(), 'region_cd', 'region_nm'),
        [
            'prompt'=>'',
        ]
    ); ?>

    <?= $form->field($model, 'kode_pos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'nomor_telp_1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_telp_2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kepala_klinik')->dropDownList(
        ArrayHelper::map(Dokter::find()->all(), 'nama', 'nama'),
        [
            'prompt'=>'',
        ]
    ); ?>

    <?= $form->field($model, 'maximum_row')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'luas_tanah')->textInput() ?>

    <?= $form->field($model, 'luas_bangunan')->textInput() ?>

    <?= $form->field($model, 'izin_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'izin_tgl')->textInput() ?>

    <?= $form->field($model, 'izin_oleh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'izin_sifat')->dropDownList([ 'Sementara' => 'Sementara', 'Tetap' => 'Tetap', 'Perpanjangan' => 'Perpanjangan', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'izin_masa_berlaku')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Simpan Perubahan Data', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
