<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosItem */
$this->title = 'Stok Opname ('. $d->item->item_nm . ') di '.$d->pos->pos_nm;
$this->params['breadcrumbs'][] = ['label' => 'Inv Pos Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pos_cd, 'url' => ['view', 'pos_cd' => $model->pos_cd, 'item_cd' => $model->item_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inv-pos-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
