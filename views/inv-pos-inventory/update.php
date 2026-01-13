<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosInventory */

$this->title = 'Ubah Pos Inventori: ' . $model->pos_cd;
$this->params['breadcrumbs'][] = ['label' => 'Pos Inventori', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_cd, 'url' => ['view', 'id' => $model->pos_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inv-pos-inventory-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
