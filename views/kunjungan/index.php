<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\bootstrap\Modal;
use dosamigos\datepicker\DatePicker;
use app\models\CaraBayar;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $jenis=='rj' ? 'Pendaftaran Rawat Jalan' : 'Pendaftaran Rawat Inap';

$unit = '';
if($jenis=='rj')
    $unit = [
                'attribute' => 'medunit_cd',
                'value' => 'unit.medunit_nm',
            ];
else
    $unit = [
                'attribute' => 'ruang_cd',
                'value' => 'ruang0.ruang_nm',
            ];

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
    <?php if($jenis=='rj'){ ?>
        <p>
            <?= Html::button('<i class="fa fa-plus"></i> Pendaftaran Kunjungan', ['value'=>Url::to(["kunjungan/create?jenis=$jenis"]),'class' => 'btn btn-success modalWindow']) ?>
            <?= Html::a('<i class="fa fa-plus"></i> Pasien Baru', Url::to(['pasien/create']),['target'=>'_blank','class' => 'btn btn-primary']) ?>
        </p>
    <?php } else { ?>
            <?= Html::button('<i class="fa fa-plus"></i> Pendaftaran Rawat Inap', ['value'=>Url::to(["kunjungan/create-rawat-inap"]),'class' => 'btn btn-success modalWindow']) ?>
            <?= Html::button('<i class="fa fa-plus"></i> Mutasi ke Rawat Inap', ['value'=>Url::to(["kunjungan/create-ranap"]),'class' => 'btn btn-circle green modalWindow']) ?>
    <?php } ?>
    <!-- <?= 
    Html::a('Sensus', Url::to(['kunjungan/sensus']),['target'=>'_blank','class' => 'btn btn-circle red-sunglo'])
    ?> -->
    <style type="text/css">
        .select2-container {
            z-index: 99999;
        }
    </style>

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
        'responsive'=>true,
        'hover'=>true,
        'pjax'=>true,
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
            $unit,
            [
                'attribute'=>'dpjp',
                'value'=>function($model){
                    return isset($model->dokter->nama) ? $model->dokter->nama : '';
                }
            ],
            [
                'attribute'=>'jam_masuk',
                'value'=>function($model){
                    return date("H:i:s", strtotime($model->jam_masuk));
                }
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'cara_id',
                'filter'=>ArrayHelper::map(CaraBayar::find()->asArray()->all(), 'cara_id', 'cara_nama'),
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>'Semua Cara Bayar'],
                'value' => function($model) {
                    $d = ArrayHelper::map(CaraBayar::find()->asArray()->all(), 'cara_id', 'cara_nama');
                    return $d[$model->cara_id];
                },
                'editableOptions' => [
                    'inputType' => '\kartik\select2\Select2',
                    'options'=>
                    [
                        'data' => ArrayHelper::map(CaraBayar::find()->asArray()->all(), 'cara_id', 'cara_nama'),
                    ],
                ],
            ],
            
            'baru_lama',
            //'jam_selesai',
            // 'status',
            // 'created',
            // 'user_input',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{process} {update} {view} {delete} {input}',
             'buttons' => [
                
                'delete' => function($url,$model) {
                    return ($model->status=='antri') ? 
                     Html::a('Hapus', $url, [
                            'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                            'title' => Yii::t('yii', 'Hapus'),
                            'data-confirm' => Yii::t('yii', 'Apakah Anda Yakin akan menghapus antrian ini?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'update' => function($url,$model) {
                    if($model->tipe_kunjungan=='Rawat Jalan')
                        return Html::button('Edit', [
                                'value'=>$url,
                                'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                                'title' => Yii::t('yii', 'Lihat'),
                                'data-pjax' => '0',
                            ]);   
                    elseif($model->tipe_kunjungan=='Rawat Inap'){
                        return Html::button('Edit', [
                                'value'=>Url::to(['kunjungan/update-rawat-inap','id'=>$model->kunjungan_id]),
                                'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                                'title' => Yii::t('yii', 'Lihat'),
                                'data-pjax' => '0',
                            ]); 
                    }
                },
                'view' => function($url,$model) {
                    return Html::button('Lihat', [
                            'value'=>$url,
                            'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                            'title' => Yii::t('yii', 'Lihat'),
                            'data-pjax' => '0',
                        ]);   
                },
                'input' => function($url,$model) {
                    if ($model->eklaim!=false)
                        return Html::button('<i class="fa fa-paperclip"></i>', [
                            'value'=>Url::to(['kunjungan-eklaim/update','id'=>$model->kunjungan_id]),
                            'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                            'title' => Yii::t('yii', 'Input Eklaim'),
                            'data-pjax' => '0',
                        ]);   
                }
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
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'))
        })
    });

JS;

$this->registerJs($script);
?>

