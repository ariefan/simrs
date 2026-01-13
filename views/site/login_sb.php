<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>


    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'user'],
        // 'fieldConfig' => [
        //     'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
        // ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'username','class'=>'form-control form-control-user']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'password','class'=>'form-control form-control-user']) ?>

        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-user btn-block', 'name' => 'login-button']) ?>
       
        
        <div class="form-actions">
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-12\">{input} {label}</div>\n<div class=\"col-lg-12\">{error}</div>",
        ]) ?>
        </div>
    <?php ActiveForm::end(); ?>



