<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMaster */

$this->title = $model->item_cd;
$this->params['breadcrumbs'][] = ['label' => 'Inv Item Master', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-master-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->item_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->item_cd], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'item_cd',
            'type_cd',
            'unit_cd',
            'item_nm',
            'barcode',
            'currency_cd',
            'item_price_buy',
            'item_price',
            'vat_tp',
            'ppn',
            'reorder_point',
            'minimum_stock',
            'maximum_stock',
            'generic_st',
            'active_st',
            'inventory_st',
            'tariftp_cd',
            'last_user',
            'last_update',
        ],
    ]) ?>

</div>
