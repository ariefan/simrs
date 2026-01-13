<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;


/* @var $this yii\web\View */
/* @var $model app\models\InvItemMove */

$this->title = 'Konversi Stok';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Konversi Stok', 'url' => ['index-konversi']];
$this->params['breadcrumbs'][] = $this->title;
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
<div class="inv-item-move-create">
    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'pos_cd')->dropDownList(ArrayHelper::map($pos_item,'pos_cd','pos_nm'), ['id'=>'pos-id','prompt'=>'Pilih Lokasi']) ?>

	 <?= 
	 $form->field($model, 'item_cd')->widget(DepDrop::classname(), [
     'options' => ['id'=>'item-cd'],
     'pluginOptions'=>[
         'depends'=>['pos-id'],
         'placeholder' => 'Pilih Item...',
         'url' => Url::to(['/inv-item-move/pos-item'])
     ]
 	]) ?> 

 	<div class="form-group">
    	<?= $form->field($model, 'old_stock')->textInput(['type' => 'decimal', 'maxlength' => true,'readonly'=>'readonly']) ?>
 	</div>

    <?= $form->field($model, 'pos_destination')->dropDownList(ArrayHelper::map($pos_all,'pos_cd','pos_nm'), ['prompt'=>'Pilih Lokasi Tujuan']) ?>

 	<div class="form-group">
    	<?= $form->field($model, 'trx_qty')->textInput(['type' => 'number', 'maxlength' => true]) ?>
 	</div>

 	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Transfer' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    

</div>

<?php
$this->registerJs("
	jQuery(document).ready(function() {
		$('#item-cd').change(function(){
			var v = $(this).val().split('|');
			
            if (typeof v[2] === 'undefined'){
                $('#invitemmove-old_stock').val(0);
                $(\"label[for='invitemmove-trx_qty']\").html('Jumlah');
            } else {
                $('#invitemmove-old_stock').val(v[1]);
                $(\"label[for='invitemmove-trx_qty']\").html('Jumlah Dalam '+v[2]);
            }
            
		})
	})

");
