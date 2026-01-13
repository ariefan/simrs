<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Bangsal;
use app\models\Kelas;

/* @var $this yii\web\View */
/* @var $model app\models\Ruang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ruang-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ruang_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kelas_cd')->dropDownList(
        ArrayHelper::map(Kelas::find()->all(),'kelas_cd','kelas_nm'),
        ['prompt'=>'silahkan pilih kelas']
    ) ?>

    <?= $form->field($model, 'bangsal_cd')->dropDownList(
        ArrayHelper::map(Bangsal::find()->all(),'bangsal_cd','bangsal_nm'),
        ['prompt'=>'silahkan pilih bangsal']
    ) ?>

    <?= $form->field($model, 'ruang_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(['1'=>'active', '0'=>'inactive']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Simpan Data Baru' : 'Simpan Perubahan Data', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
