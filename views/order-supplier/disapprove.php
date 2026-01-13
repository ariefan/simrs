<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSupplier */

$this->title = "Disapprove Order ";
$this->params['breadcrumbs'][] = ['label' => 'Order Suppliers', 'url' => ['index']];
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

<div class="order-supplier-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'order_id',
            'order_kode',
            'order_tanggal',
            'supplier.supplier_nama',
            'cabang.cabang_nama',
            'status',
            'catatan',
            'total_harga:decimal',
            //'user_id',
            'created',
            'modified',
        ],
    ]) ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty Order</th>
                <th>Qty Terima</th>
                <th>Kondisi Terima</th>
                <th>Satuan</th>
                <th>Harga Item</th>
                <th>Total Harga</th>                
            </tr>
        </thead>
        <tbody id="item_detail">
        <?php 
        foreach ($item_exist as $key => $value) {
            echo '<tr>
                <td>'.$value['barang']['barang_nama'].'</td>
                <td>'.$value['jumlah_order_supplier'].'</td>
                <td>'.$value['jumlah_receive_supplier'].'</td>
                <td>'.$value['kondisi_receive'].'</td>
                <td>'.$value['satuan_supplier'].'</td>
                <td>'.number_format($value['harga_supplier'],0,'','.').'</td>
                <td>'.number_format($value['harga_supplier']*$value['jumlah_order_supplier'],0,'','.').'</td>
            </tr>';
        } ?>
        </tbody>

    </table>

</div>

<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>
	
    <div class="form-group">
        <?= Html::submitButton('Disapprove', ['class' => 'btn btn-danger']) ?>
    </div>

<?php ActiveForm::end(); ?>
