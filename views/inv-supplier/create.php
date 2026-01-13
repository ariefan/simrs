<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvSupplier */

$this->title = 'Tambah Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-supplier-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
