<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\BaseUrl;

/* @var $this yii\web\View */
/* @var $model app\models\InvSupplier */

$this->title = $model->supplier_cd;
$this->params['breadcrumbs'][] = ['label' => 'Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-supplier-view">

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->supplier_cd], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->supplier_cd], [
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
            'supplier_cd',
            'supplier_nm',
            'address:ntext',
            'city',
            'province',
            'postcode',
            'phone',
            'mobile',
            'fax',
            'email:email',
            'npwp',
            [
                'attribute'=>'pic',
                'format'=>['image',['width'=>'100']],
                'value'=>BaseUrl::base(true).'/foto_supplier/'.$model->pic
            ]
        ],
    ]) ?>

</div>
