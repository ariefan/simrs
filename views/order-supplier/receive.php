<?php

use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\InvSupplier;
use dosamigos\datepicker\DatePicker;


/* @var $this yii\web\View */
/* @var $model app\models\OrderSupplier */

$this->title = 'Receive Order Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Order Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Order', 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = 'Update';
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
<div class="order-supplier-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'order_id',
            'order_kode',
            'order_tanggal',
            'supplier.supplier_nm',
            //'status',
            'catatan',
            'total_harga:decimal',
            //'user_id',
            //'created',
            //'modified',
        ],
    ]) ?>
    <div class="order-supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <table class="table table-bordered">
	        <thead>
	            <tr>
	                <th>Item</th>
                    <th>Jml Order</th>
	                <th>Sudah Diterima</th>
	                <th>Jml Terima</th>
	                <th>Kondisi</th>
                    <th>Satuan</th>
	                <th>Expired Date</th>
	                <th>Harga Item</th>
	                <th>Total Harga</th>                
	            </tr>
	        </thead>
	        <tbody id="item_detail">
	        <?php 
	        foreach ($item_exist as $key => $value) {
                $default_expired = (date('Y')+5).'-12-31';
                $value['jumlah_receive_supplier'] = empty($value['jumlah_receive_supplier']) ? 0 : $value['jumlah_receive_supplier'];
	            echo '<tr>
	                <td>'.$value['barang']['item_nm'].'</td>
                    <td>'.$value['jumlah_order_supplier'].'</td>
	                <td>'.$value['jumlah_receive_supplier'].'</td>
	                <td><input type="number" value="'.($value['jumlah_order_supplier']-$value['jumlah_receive_supplier']).'" name="jumlah_receive['.$value['item_cd'].']" class="JML form-control" /></td>
	                <td><input type="text" value="baik" name="kondisi_receive['.$value['item_cd'].']" class="form-control" /></td>
	                <td>'.$value['satuan_supplier'].'</td>
                    <td><input type="date" name="expired_date['.$value['item_cd'].']" value="'.$default_expired.'" class="form-control" /></td>
	                <td><input type="text" class="form-control HB" name="harga_baru['.$value['item_cd'].']" value="'.$value['harga_supplier'].'"/></td>
                    
                <td>'.number_format($value['harga_supplier']*($value['jumlah_order_supplier']-$value['jumlah_receive_supplier']),0,'','.').'</td>
	            </tr>';
	        } ?>
	        </tbody>

	    </table>
    </div>

    
    <?= $form->field($model, 'no_faktur')->textInput(['maxlength' => true]) ?>

     <?= $form->field($model, 'jatuh_tempo')->widget(
        DatePicker::className(),[
            'inline'=>false,
            //'template'=>'<div class="well well-sm" style="background-color: #fff; width: 250px">{input}</div>',
            'clientOptions'=>[
                'autoclose'=>true,
                'format'=>'yyyy-mm-dd'
            ]
        ]);?>

    <?= $form->field($model, 'catatan_receive')->textInput(['maxlength' => true]) ?>    

    <div class="form-group">
        <?= Html::submitButton('Terima', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>

</div>

<?php
$this->registerJs("
    jQuery(document).ready(function() {
        $('.HB').keyup(function(){
            var currHargaBeli = $(this).val();
            var jumlah = $(this).parent().prev().prev().prev().find('.form-control').val();

            $(this).parent().next().next().html(accounting.formatNumber(currHargaBeli * jumlah));
        });

        $('.JML').keyup(function(){
            var currHargaBeli = $(this).parent().next().next().next().find('.form-control').val();
            var jumlah = $(this).val();

            $(this).parent().next().next().next().next().next().html(accounting.formatNumber(currHargaBeli * jumlah));
        });
    });");
?>