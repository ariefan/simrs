<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\AccountGroup;


/* @var $this yii\web\View */
/* @var $model app\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'accgroup_cd')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map(AccountGroup::find()->select(['accgroup_cd', 'accgroup_nm'])->asArray()->all(), 'accgroup_cd', 'accgroup_nm'),
        'options' => ['placeholder' => 'accgroup_cd'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'account_nm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'default_amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_no')->textInput() ?>

    <?= $form->field($model, 'print_single_st')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
