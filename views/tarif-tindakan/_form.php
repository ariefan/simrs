<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Asuransi;
use app\models\Kelas;
use app\models\Account;
use app\models\Tindakan;
use kartik\select2\Select2;



/* @var $this yii\web\View */
/* @var $model app\models\TarifTindakan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-tindakan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        $model->tarif_tindakan_id = $model->idInsert; 

        echo $form->field($model, 'tarif_tindakan_id')->textInput(['maxlength' => true]);
    ?>

    <?= $form->field($model, 'insurance_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(Asuransi::find()->select(['insurance_cd', 'insurance_nm'])->asArray()->all(), 'insurance_cd', 'insurance_nm'),
        'options' => ['placeholder' => 'insurance_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'kelas_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(Kelas::find()->select(['kelas_cd', 'kelas_nm'])->asArray()->all(), 'kelas_cd', 'kelas_nm'),
        'options' => ['placeholder' => 'insurance_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'treatment_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(Tindakan::find()->select(['tindakan_cd', 'nama_tindakan'])->asArray()->all(), 'tindakan_cd', 'nama_tindakan'),
        'options' => ['placeholder' => 'tindakan_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'tarif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(Account::find()->select(['account_cd', 'account_nm'])->asArray()->all(), 'account_cd', 'account_nm'),
        'options' => ['placeholder' => 'account_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
