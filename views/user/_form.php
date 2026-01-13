<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Role;
use app\models\Bangsal;
use app\models\UnitMedis;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

<?= $form->field($model, 'username') ?>

<?= empty($model->username) ? $form->field($model, 'password')->passwordInput() :  $form->field($model, 'password_hash')->passwordInput() ?>


<?= $form->field($model, 'role')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Role::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Pilih Role...'],
        
    ]);
?>

<?= $form->field($model, 'bangsal_cd')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Bangsal::find()->all(), 'bangsal_cd', 'bangsal_nm'),
        'options' => [
            'placeholder' => 'Pilih Bangsal...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        
    ]);
?>

<?= $form->field($model, 'medunit_cd')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(UnitMedis::find()->all(), 'medunit_cd', 'medunit_nm'),
        'options' => ['placeholder' => 'Pilih Unit...'],
        'pluginOptions' => [
            'allowClear' => true
        ],

    ]);
?>

<div class="form-group">
    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>

<?php ActiveForm::end(); ?>
            

    

