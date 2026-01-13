<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JenisKunjungan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jenis-kunjungan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jns_kunjungan_id')->textInput() ?>

    <?= $form->field($model, 'jns_kunjungan_nama')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
