<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvSupplier */

$this->title = 'Ubah Supplier: ' . $model->supplier_cd;
$this->params['breadcrumbs'][] = ['label' => 'Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->supplier_cd, 'url' => ['view', 'id' => $model->supplier_cd]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="inv-supplier-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
