<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tracking RM';
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
<p>
    <?= Html::a('Rekap Kunjungan', Url::to(['kunjungan/rekap-kunjungan']),['target'=>'_blank','class' => 'btn btn-circle red-sunglo']) ?>
</p>
<div class="table-responsive">
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
                'format' => 'raw',
                'value' => function($model){
                    return "<a class ='modalWindow' href='javascript:;' value='".Url::to(['pasien/view-ajax','id'=>$model->mr])."'>".$model->mr0->nama."</a>"; 
                }
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

            [
                'attribute' => 'cara_id',
                'value' => 'cara.cara_nama'
            ],
            [
                'attribute'=>'jam_masuk',
                'value'=>function($model){
                    return date("H:i:s", strtotime($model->jam_masuk));
                }
            ],
            //'jam_selesai',
            // 'status_rm',
            [
                'attribute'=>'status_rm',
                'filter'=>['Datang','Ketemu','Dikirim','Kembali'],
            ],
            'rm_ketemu',
            'rm_dikirim',
            'rm_kembali',
            'baru_lama',
            // 'created',
            // 'user_input',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{ketemu} {batal-ketemu} {kirim} {batal-kirim} {kembali} {batal-kembali}',

             'buttons' => [
                'ketemu' => function($url,$model) {
                    return ($model->status_rm=='Datang') ? Html::a('<span class="fa fa-check"></span>', $url, [
                            'title' => Yii::t('yii', 'Ketemu'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Ketemu?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'batal-ketemu' => function($url,$model) {
                    return ($model->status_rm=='Ketemu') ? Html::a('<span class="fa fa-undo"></span>', $url, [
                            'title' => Yii::t('yii', 'Batal Ketemu'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Batal Ketemu?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'kirim' => function($url,$model) {
                    return ($model->status_rm=='Ketemu') ? Html::a('<span class="fa fa-check"></span>', $url, [
                            'title' => Yii::t('yii', 'Kirimkan'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Kirimkan?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'batal-kirim' => function($url,$model) {
                    return ($model->status_rm=='Dikirim') ? Html::a('<span class="fa fa-undo"></span>', $url, [
                            'title' => Yii::t('yii', 'Batal Kirim'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Batal Kirim?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'kembali' => function($url,$model) {
                    return ($model->status_rm=='Dikirim') ? Html::a('<span class="fa fa-check"></span>', $url, [
                            'title' => Yii::t('yii', 'Kembali'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Sudah Kembali?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'batal-kembali' => function($url,$model) {
                    return ($model->status_rm=='Kembali') ? Html::a('<span class="fa fa-undo"></span>', $url, [
                            'title' => Yii::t('yii', 'Batal Kembali'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Batal Kembali?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
             ]
            ],
        ],
    ]); ?>
    </div>
</div>

<?php
   Modal::begin([
            'header' => '<h4>Pasien</h4>',
           'options' => [
                'id' => 'kartik-modal',
                'tabindex' => false // important for Select2 to work properly
            ],
        ]);

    echo "<div id='modalContent'></div>";

    Modal::end();

?>

<?php

$script = <<< JS
    $(function(){
        $('.modalWindow').click(function(){
            $('#kartik-modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'))
        })
    });

JS;

$this->registerJs($script); 
?>