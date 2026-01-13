<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Klinik;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
$this->title = 'Ganti Password';
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if(Yii::$app->session->getFlash('error')): ?>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
    <?= Yii::$app->session->getFlash('error'); ?>
</div>
<?php endif; ?>
<?php if(Yii::$app->session->getFlash('success')): ?>
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
    <?= Yii::$app->session->getFlash('success'); ?>
</div>
<?php endif; ?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'newPassword')->passwordInput() ?>
<?= $form->field($model, 'newPasswordConfirm')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton('Ganti Password', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>
            

    

