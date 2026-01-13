<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OrderSupplier */

$this->title = 'Buat Order Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Order Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-supplier-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
