<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSupplier */

$this->title = 'Update Order Supplier: ' . ' ' . $model->order_kode;
$this->params['breadcrumbs'][] = ['label' => 'Order Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Order', 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-supplier-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'item_exist' => $item_exist
    ]) ?>

</div>
