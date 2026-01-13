<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvUnit */

$this->title = 'Ubah Satuan: ' . $model->unit_cd;
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->unit_cd, 'url' => ['view', 'id' => $model->unit_cd]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="inv-unit-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
