<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'menu_root')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($model->find()->where(['menu_root'=>''])->all(), 'menu_id', 'menu_nama'),
        'options' => ['placeholder' => 'Pilih Menu Root...'],
        
    ]);
    ?>

    <?= $form->field($model, 'menu_nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu_icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu_order')->textInput() ?>

    <?= $form->field($model, 'menu_route')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
