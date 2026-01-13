<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InvBatchItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="inv-batch-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_cd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'trx_qty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'batch_no_start')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'batch_no_end')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_date')->textInput() ?>

    <?= $form->field($model, 'modi_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'modi_datetime')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
