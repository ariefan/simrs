<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\bridging\Eklaim;
/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaim */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="kunjungan-eklaim-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nomor_sep')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'kelas_rawat')->dropDownList(Eklaim::kelas_rawatOPT()) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'add_payment_pct')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'birth_weight')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'payor_id')->dropDownList(Eklaim::payorOPT()) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cob_cd')->dropDownList(Eklaim::cob_cdOPT()) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'upgrade_class_class')->dropDownList(Eklaim::upgrade_class_classOPT()) ?>
        </div>
        <div class="col-md-6">

        </div>   
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Simpan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
