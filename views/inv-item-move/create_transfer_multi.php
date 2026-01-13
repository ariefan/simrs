<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMove */

$this->title = 'Transfer Stok';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Transfer Stok', 'url' => ['index']];
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
    <?php $form = ActiveForm::begin(['id'=>'form-transfer']); ?>

	<?= $form->field($model, 'pos_cd')->dropDownList(ArrayHelper::map($pos_item,'pos_cd','pos_nm'), ['id'=>'pos-id','prompt'=>'Pilih Lokasi']) ?>

    
    <div id="data_item">
    <?php if(!empty($data_item)): ?>
	
    <?= $form->field($model, 'multi_barang')->widget(MultipleInput::className(), [
    'columns' => [
            [
                'name'  => 'item_cd',
                'type'  => \kartik\select2\Select2::className(),
                'title' => 'Barang',
                'options' => [
                    'data' => ArrayHelper::map($data_item,'id','name'),
                ],
            ],
            [
                'name'  => 'trx_qty',
                'title' => 'Jumlah',
                'defaultValue' => 1,
                'enableError' => true,
                'options' => [
                    'type' => 'number',
                    'class' => 'input-priority',
                ]
            ],
            
        ]
     ]);
    ?>

    <?= $form->field($model, 'pos_destination')->dropDownList(ArrayHelper::map($pos_all,'pos_cd','pos_nm'), ['prompt'=>'Pilih Lokasi Tujuan']) ?>

    <div class="form-group">
        <?= Html::submitButton('Transfer', ['class' => 'btn btn-success']) ?>
    </div>

    <?php endif; ?>
    
    </div>

    <?php ActiveForm::end(); ?>
    

</div>

<?php
$this->registerJs("
	jQuery(document).ready(function() {
		$('#pos-id').change(function(){
            $('#data_item').html('');
			$('#form-transfer').submit();
		})
	})

");
