<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use app\models\bridging\Eklaim;
/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaim */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="kunjungan-eklaim-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'nomor_sep')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'kelas_rawat')->dropDownList(Eklaim::kelas_rawatOPT()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'add_payment_pct')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'birth_weight')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'payor_id')->dropDownList(Eklaim::payorOPT()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'cob_cd')->dropDownList(Eklaim::cob_cdOPT()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'upgrade_class_class')->dropDownList(Eklaim::upgrade_class_classOPT()) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'adl_sub_acute')->textInput(['type'=>'number'])->input('adl_sub_acute', ['placeholder' => "12 - 60"]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'adl_chronic')->textInput(['type'=>'number'])->input('adl_chronic', ['placeholder' => "12 - 60"]) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'icu_indikator')->dropDownList(Eklaim::icu_indikatorOPT()) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'icu_los')->textInput(['type'=>'number']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'ventilator_hour')->textInput(['type'=>'number']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'discharge_status')->dropDownList(Eklaim::discharge_statusOPT()
            ) ?>
        </div>

        <div class="col-md-4">
            <?php
            $model->procedure = $model->procedures;
            echo $form->field($model, 'procedure')->widget(Select2::classname(), [
            'options' => ['placeholder' => 'Pilih procedure', 'multiple' => true],
            'initValueText' => $model->proceduresText,
            'value' => $model->procedures,
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['diagnosis/cari-procedure']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(dat) { return dat.text; }'),
                'templateSelection' => new JsExpression('function (dat) { return dat.text; }'),
            ],
        ]); ?>
        </div>
    </div>
<hr>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'tarif_poli_eks')->textInput(['type'=>'number']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tarif_rs')->textInput(['type'=>'number']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'upgrade_class_los')->textInput(['type'=>'number']) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Simpan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<< JS
    $(function(){
        function icu_indi(){
            if ($('#kunjunganeklaim-icu_indikator').val()=='0'){
                $('#kunjunganeklaim-icu_los').val('0');
                $('#kunjunganeklaim-ventilator_hour').val('0');
            }
        }
        icu_indi();
        $('#kunjunganeklaim-icu_indikator').change(function(){
            icu_indi();
        });
    });

JS;

$this->registerJs($script);
?>