<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
$this->title = "".($cat==1)?"Menu Laporan Alat":"Menu Laporan Bahan";
$this->params['breadcrumbs'][] = ['label' => 'Order Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>


<?php $form = ActiveForm::begin(); ?>
<input type="hidden" name="cat" value="<?= $cat ?>">
<?= Select2::widget([
    'name' => 'PO',
    'value' => '',
    'data' => $list,
    'options' => ['placeholder' => 'Pilih Order Kode...']
]); ?>
<br/>
<?= Select2::widget([
    'name' => 'stat',
    'value' => '',
    'data' => ['ordered'=>'Ordered','approved'=>'Approved', 'received'=> 'Received'],
    'options' => ['placeholder' => 'Pilih Status...']
]); ?>
<br/>
<div class="form-group">
	<input type="submit"  name='tipe' value="Cetak Laporan" class="btn btn-success">
	<input type="submit" name='tipe' value="Download Laporan" class="btn btn-primary">
</div>
<?php ActiveForm::end(); ?>
