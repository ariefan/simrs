<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\InvPosItem */

$this->title = 'Stok ('. $d->item->item_nm . ') di '.$d->pos->pos_nm;
$this->params['breadcrumbs'][] = ['label' => 'Inv Pos Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-pos-item-view">



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pos_cd',
            'pos.pos_nm',
            'item_cd',
            'item.item_nm',
            'quantity:decimal',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>
    <h2>Batch Item</h2>
    <?= GridView::widget([
        'dataProvider' => $dataBatch,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'batch_no',
            'trx_qty:decimal',
            'expire_date',
            'modi_id',
            'modi_datetime',

        ],
    ]); ?>
    <h2>Kartu Stok</h2>
    <?= GridView::widget([
        'dataProvider' => $dataMove,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'move_tp',

            // 'id',
            [
                'attribute'=>'pos_cd',
                'value'=>'posCd.pos_nm'
            ],
            [
                'attribute'=>'pos_destination',
                'value'=>'posDestination.pos_nm'
            ],
            // 'item_cd',
            // 'trx_by',
            // 'trx_datetime',
            'trx_qty',
            'old_stock',
            'new_stock',
            'purpose:ntext',
            // 'vendor',
            'note:ntext',
            'modi_id',
            'modi_datetime',

        ],
    ]); ?>
</div>
