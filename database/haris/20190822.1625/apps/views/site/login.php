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
<style type="text/css">
    .logo {
        display: none;
    }
    .kt-login__body {
        margin-top: 80px;
        padding-bottom: 100px;
    }
</style>
        <div class="kt-grid kt-grid--ver kt-grid--root">
            <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
                <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
                    <div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
                        <div class="kt-login__wrapper">
                            <div class="kt-login__container">
                                <div class="kt-login__body">
                                    <div class="kt-login__logo">
                                        <a href="#">
                                            <img src="/metronic6/media/logo.png">
                                        </a>
                                    </div>
                                    <div class="kt-login__signin">
                                        <div class="kt-login__head">
                                            <h3 class="kt-login__title">Sign In To SIMRS</h3>
                                        </div>
                                        <div class="kt-login__form">

                                            <?php $form = ActiveForm::begin([
                                                'id' => 'login-form',
                                                'options' => ['class' => 'kt-form'],
                                                'fieldConfig' => [
                                                    'template' => "{input}",
                                                ],
                                            ]); ?>

                                            <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder'=>'username']) ?>

                                            <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'password']) ?>

                                                <!--begin::Action-->
                                                <div class="kt-login__actions">
                                                    <a href="#" class="kt-link kt-login__link-forgot">
                                                        &nbsp;
                                                    </a>

                                                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-elevate kt-login__btn-primary uppercase', 'name' => 'login-button']) ?>
                                                </div>

                                                <!--end::Action-->
                                            <?php ActiveForm::end(); ?>

                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                            <p>&nbsp;</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background-image: url(/metronic6/media/bg-4.jpg);">
                        <div class="kt-login__section">
                            <div class="kt-login__block">
                                <h3 class="kt-login__title">R S U D &nbsp; W a t e s</h3>
                                <div class="kt-login__desc" style="font-size: 2rem">
                                    Ikhlas Sepenuh Hati
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>