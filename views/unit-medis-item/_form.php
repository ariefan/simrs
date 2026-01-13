<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\UnitMedis;
use app\models\UnitMedisItem;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model app\models\UnitMedisItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-medis-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= 
           $form->field($model, 'medicalunit_cd')->textInput(['maxlength' => true])
    ?>
    

    <?= $form->field($model, 'medunit_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(UnitMedis::find()->select(['medunit_cd', 'medunit_nm'])->asArray()->all(), 'medunit_cd', 'medunit_nm'),
        'options' => ['placeholder' => 'medunit_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); 
    ?>

    <?= $form->field($model, 'medicalunit_root')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(UnitMedisItem::find()->select(['medicalunit_cd', 'medicalunit_nm'])->asArray()->all(), 'medicalunit_cd', 'medicalunit_nm'),
        'options' => ['placeholder' => 'medicalunit_root'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'medicalunit_nm')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Simpan Data Baru' : 'Simpan Perubahan Data', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
