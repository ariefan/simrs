<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplier';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-supplier-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah supplier', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'supplier_cd',
            'supplier_nm',
            'address:ntext',
            'city',
            'province',
            // 'postcode',
            // 'phone',
            // 'mobile',
            // 'fax',
            // 'email:email',
            // 'npwp',
            // 'pic',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
