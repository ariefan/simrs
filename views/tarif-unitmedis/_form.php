<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Asuransi;
use app\models\Kelas;
use app\models\UnitMedisItem;
use app\models\Account;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TarifUnitmedis */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-unitmedis-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        $model->tarif_unitmedis_id = $model->idInsert; 

        echo $form->field($model, 'tarif_unitmedis_id')->textInput(['maxlength' => true]);
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

    <?= $form->field($model, 'medicalunit_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(UnitMedisItem::find()->select(['medicalunit_cd', 'medicalunit_nm'])->asArray()->all(), 'medicalunit_cd', 'medicalunit_nm'),
        'options' => ['placeholder' => 'medicalunit_cd'],
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
