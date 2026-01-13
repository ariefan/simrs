<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\InvItemMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inventori Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-master-index">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_cd',
            'type_cd',
            'unit_cd',
            'item_nm',
            'barcode',
            // 'currency_cd',
            // 'item_price_buy',
            // 'item_price',
            // 'vat_tp',
            // 'ppn',
            // 'reorder_point',
            // 'minimum_stock',
            // 'maximum_stock',
            // 'generic_st',
            // 'active_st',
            // 'inventory_st',
            // 'tariftp_cd',
            // 'last_user',
            // 'last_update',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
