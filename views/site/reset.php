<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var amnah\yii2\user\models\User $user
 * @var bool $success
 * @var bool $invalidToken
 */

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-reset">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!empty($success)): ?>

        <div class="alert alert-success">

            <p>Password Telah di Reset</p>
            <p><?= Html::a("Silahkan Login Disini", Url::to(["site/login"])) ?></p>

        </div>

    <?php elseif (!empty($invalidToken)): ?>

        <div class="alert alert-danger">
            <p>Token Tidak Valid</p>
        </div>

    <?php else: ?>

        <div class="row">
            <div class="col-lg-12">

                <div class="alert alert-warning">
                    <p>Email [ <?= $user->email ?> ]</p>
                </div>

                <?php $form = ActiveForm::begin([
                        'id' => 'reset-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "<div class=\"col-lg-12\">{input}</div>\n<div class=\"col-lg-12\">{error}</div>",
                        ],
                    ]); ?>

                    <?= $form->field($reset, 'newPassword')->passwordInput(['placeholder'=>'Ketik Password']) ?>
                    <?= $form->field($reset, 'newPasswordConfirm')->passwordInput(['placeholder'=>'Ulangi Ketik Password']) ?>
                    <div class="form-group">
                        <?= Html::submitButton('Reset', ['class' => 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <?php endif; ?>

</div>