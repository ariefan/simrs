<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrderSupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Suppliers';
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->user->identity->role = Yii::$app->user->identity->role;
?>
<?php if(Yii::$app->session->getFlash('error')): ?>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
    <?= Yii::$app->session->getFlash('error'); ?>
</div>
<?php endif; ?>


<?php if(Yii::$app->session->getFlash('success')): ?>
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
    <?= Yii::$app->session->getFlash('success'); ?>
</div>
<?php endif; ?>
<div class="order-supplier-index">

    <p>
        <?= Html::a('Buat Order Supplier', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'order_id',
            'order_kode',
            'order_tanggal',
            [
                'attribute'=>'supplier_cd',
                'value'=>'supplier.supplier_nm'
            ],
            
            'status',
            'catatan',
            // 'total_harga',
            // 'user_id',
            //'created',
            // 'modified',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{view} {update} {delete} {approve} {receive} {cancel-receive}',
             'buttons' => [
                'view' => function($url,$model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                        'title' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ]);
                },
                'update' => function($url,$model) {
                    return ($model->status=='ordered') ?
                        Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ]): "";
                },
                'approve' => function($url,$model) {
                    return (($model->status=='ordered')&& (Yii::$app->user->identity->role==10)) ?
                        Html::a('<span class="glyphicon glyphicon-ok"></span>', $url, [
                            'title' => Yii::t('yii', 'Approve'),
                            'data-confirm' => Yii::t('yii', 'Apakah anda yakin akan melakukan approve atas order ini??'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'delete' => function($url,$model) {
                    return $model->status=='ordered' ?
                        Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('yii', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Apakah Anda Yakin akan menghapus order ini?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";    
                },
                'receive' => function($url,$model) {
                    return ($model->status=='approved'||$model->status=='received') ?
                        Html::a('<span class="glyphicon glyphicon-save"></span>', $url, [
                            'title' => Yii::t('yii', 'Receive'),
                            'data-pjax' => '0',
                        ]): "";
                },
                // 'cancel-receive' => function($url,$model) {
                //     return ($model->status=='received') ?
                //         Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                //             'title' => Yii::t('yii', 'Cancel Receive'),
                //             'data-confirm' => Yii::t('yii', 'Apakah anda yakin akan melakukan pembatalan penerimaan atas order ini??'),
                //             'data-method' => 'post',
                //             'data-pjax' => '0',
                //         ]) : "";  
                // }
              ]
            ],
        ],
    ]); ?>

</div>
