<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\Modal;
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
    <div id="modal" class="fade modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Pasien</h4>
                </div>
                <div class="modal-body">
                    <div id='modalContent'></div>
                </div>

            </div>
        </div>
    </div>
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
                'value' => 'mr0.nama'
            ],
            [
                'attribute' => 'Jenis Kelamin',
                'value' => 'mr0.jk'
            ],
            [
                'attribute'=>'dpjp',
                'value'=>function($model){
                    return isset($model->dokter->nama) ? $model->dokter->nama : '';
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
            'jam_masuk',
            [
                'attribute' => 'ruang_cd',
                'value' => 'ruang0.ruang_nm',
            ],
            //'jam_selesai',
            'status',
            //'jam_masuk',
            //'jam_selesai',
            //'status',
            // 'created',
            // 'user_input',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{process} {view} {update} {mutasi}',

             'buttons' => [
                'process' => function($url,$model) {
                    return ($model->status=='antri') ? 
                     Html::a('proses', Url::to(['rekam-medis/create','kunjungan_id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->kunjungan_id, Yii::$app->params['kunciInggris'] ))]), [
                            'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                            'title' => Yii::t('yii', 'Proses'),
                            'data-pjax' => '0',
                        ]) : ""; 
                },
                'update' => function($url,$model) {

                    return (isset($model->rekamMedis[0]->rm_id) && ($model->status=='antri obat' || $model->status=='antri periksa' || $model->status=='diperiksa')) ? 
                     Html::a('update', Url::to(['rekam-medis/update','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]), [
                            'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                            'title' => Yii::t('yii', 'Proses'),
                            'data-pjax' => '0',
                        ]) : "";  
                },
                
                'view' => function($url,$model) {
                    if(isset($model->rekamMedis[0]->rm_id))
                        return Html::button('Lihat', [
                            'value'=>Url::to(['rekam-medis/view-ajax','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]),
                            'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                            'title' => Yii::t('yii', 'Lihat'),
                            'data-pjax' => '0',
                        ]);  
                    else
                        return Html::button('Lihat', [
                                'value'=>$url,
                                'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                                'title' => Yii::t('yii', 'Lihat'),
                                'data-pjax' => '0',
                            ]);  
                },
                
             ]
            ],
        ],
    ]); ?>
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
?>

