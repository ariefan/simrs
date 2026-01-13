<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pemeriksaan';
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
<div class="kunjungan-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    

<div class="table-responsive">
<?php Pjax::begin(['id' => 'gridData']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'kunjungan_id',
            //'klinik_id',
            'mr',
            [
                'attribute' => 'pasien_nama',
                'value' => 'mr0.nama'
            ],
            [
                'attribute' => 'tanggal_periksa',
                'format' => ['date', 'php:d-F-Y'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'tanggal_periksa',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])
            ],
            [
                'attribute' => 'medunit_cd',
                'value' => 'unit.medunit_nm',
            ],
            'jam_masuk',
            //'jam_selesai',
            //'jam_masuk',
            //'jam_selesai',
            //'status',
            // 'created',
            // 'user_input',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{mutasi}',
             'buttons' => [
                'mutasi' => function($url,$model) {
                    return ($model->canMutasi)? Html::button('Mutasi', [
                            'value'=>$url,
                            'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                            'title' => Yii::t('yii', 'Daftar'),
                            'data-pjax' => '0',
                        ]):"";  
                }
             ]
            ],
        ],
    ]); ?>
    <?php Pjax::end();?>   
    </div>
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
$this->registerJs(
        "$(document).on('ready pjax:success', function() {
                $('.modalWindow').click(function(){
                    $('#modal').modal('show')
                        .find('#modalContent')
                        .load($(this).attr('value'))
                })
            });
        ");


?>

<?php
        Modal::begin([
                'id' => 'modal',
            ]);

        echo "<div id='modalContent'></div>";

        Modal::end();

    ?>