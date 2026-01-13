<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Pendaftaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="form-title">
        <span class="form-title">Selamat Datang Dokter.</span>
        <span class="form-subtitle">Silahkan Melakukan Pengisian Data.</span>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'username']) ?>
        <?= $form->field($model, 'email')->textInput(['autofocus' => true,'placeholder'=>'email']) ?>

        <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'password']) ?>
        <?= $form->field($model, 'password2')->passwordInput(['placeholder'=>'konfirmasi password']) ?>

        

        <div class="form-actions">
            <?= Html::submitButton('daftar', ['class' => 'btn red btn-block uppercase', 'name' => 'daftar-button']) ?>
        </div>
        <div class="form-actions">
            <?= Html::a('login',Url::to(['site/login']),['class'=>'btn white btn-block uppercase']) ?>
        </div>
        <div class="form-actions">
            <?= Html::a('Lupa Password',Url::to(['site/forgot']),['class'=>'btn btn-primary']) ?>
            <?= Html::a('Terms of Use',Url::to(['site/terms']),['class'=>'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
