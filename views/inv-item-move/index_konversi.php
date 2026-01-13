<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;


/* @var $this yii\web\View */
/* @var $searchModel app\models\InvItemMoveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Konversi Stok';
$this->params['breadcrumbs'][] = $this->title;
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
<div class="inv-item-move-index">
    <?php
        Modal::begin([
                'header' => '<h4>Item</h4>',
                'id' => 'modal',
            ]);

        echo "<div id='modalContent'></div>";

        Modal::end();

    ?>

    <p>
        <?= Html::a('Konversi Stok', ['create-konversi'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
             [
                'attribute'=>'pos_cd',
                'value'=>'posCd.pos_nm'
            ],
             [
                'attribute'=>'pos_destination',
                'value'=>'posDestination.pos_nm'
            ],
            'item_cd',
            //'trx_by',
            //'trx_datetime',
            'trx_qty:decimal',
            // 'old_stock',
            // 'new_stock',
            'purpose:ntext',
            //'vendor',
            //'move_tp',
            //'note:ntext',
            //'modi_id',
            'modi_datetime',

            ['class' => 'yii\grid\ActionColumn','template' => '{view}'],
        ],
    ]); ?>
</div>


<?php

$script = <<< JS
    $(function(){
        $('.modalWindow').click(function(){
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'))
        })
    });

JS;

$this->registerJs($script);
?>