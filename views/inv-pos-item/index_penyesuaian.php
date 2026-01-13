<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvPosItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penyesuaian (stok opname)';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-pos-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'pos_cd',
                'value'=>'pos.pos_nm'
            ],
            [
                'attribute'=>'item_cd',
                'value'=>'item.item_nm'
            ],
            'quantity:decimal',
            'modi_id',
            'modi_datetime',

            ['class' => 'yii\grid\ActionColumn','template'=>'{view} {update}'],
        ],
    ]); ?>
</div>
