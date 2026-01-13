<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Asuransi;
use app\models\Kelas;
use app\models\Account;
use app\models\InvItemMaster;


/* @var $this yii\web\View */
/* @var $model app\models\TarifInventori */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-inventori-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        $model->seq_no = $model->idInsert; 

        echo $form->field($model, 'seq_no')->textInput(['maxlength' => true]);
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

    <?= $form->field($model, 'item_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(InvItemMaster::find()->select(['item_cd', 'item_nm'])->asArray()->all(), 'item_cd', 'item_nm'),
        'options' => ['placeholder' => 'item_cd'],
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
