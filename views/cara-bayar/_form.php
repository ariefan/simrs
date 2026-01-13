<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CaraBayar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cara-bayar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cara_nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cara_tipe')->dropDownList([ 'Membayar' => 'Membayar', 'Asuransi' => 'Asuransi', 'Gratis' => 'Gratis', ], ['prompt' => '']) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
