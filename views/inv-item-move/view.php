<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMove */

$this->title = $model->item_cd;
$this->params['breadcrumbs'][] = ['label' => 'Barang Masuk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-move-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            // 'id',
            'posCd.pos_nm',
            'posDestination.pos_nm',
            'itemCd.item_nm',
           // 'trx_by',
            'trx_datetime',
            'trx_qty:decimal',
            'old_stock:decimal',
            'new_stock:decimal',
            'purpose:ntext',
           // 'vendor',
            'move_tp',
            'note',
            'modi_id',
            'modi_datetime',
            
        ],
    ]) ?>

</div>
