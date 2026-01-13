<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvBatchItem */
?>
<div class="inv-batch-item-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'batch_no',
            'pos_cd',
            'item_cd',
            'supplier',
            'trx_qty',
            'batch_no_start',
            'batch_no_end',
            'buy_price',
            'sell_price',
            'sell_price_2',
            'expire_date',
            'order_id',
            'modi_id',
            'modi_datetime',
        ],
    ]) ?>

</div>
