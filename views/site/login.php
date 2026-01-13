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
<div class="site-login">
    <div class="form-title">
        <span class="form-title">Selamat Datang.</span>
        <span class="form-subtitle">Silahkan login.</span>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'username']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'password']) ?>

        <div class="form-actions">
            <?= Html::submitButton('Login', ['class' => 'btn red btn-block uppercase', 'name' => 'login-button']) ?>
        </div>    
       
        
        <div class="form-actions">
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"col-lg-offset-1 col-lg-12\">{input} {label}</div>\n<div class=\"col-lg-12\">{error}</div>",
        ]) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>



