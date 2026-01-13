<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use app\models\Dokter;
use kartik\grid\GridView;

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
            // [
            //     'attribute'=>'dpjp',
            //     'value'=>function($model){
            //         return isset($model->dokter->nama) ? $model->dokter->nama : '';
            //     }
            // ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'dpjp',
                'filter'=>ArrayHelper::map(Dokter::find()->joinWith('user')->where(['role'=>25])->asArray()->all(), 'user_id', 'nama'),
                
                'filterWidgetOptions'=>[
                    'pluginOptions'=>['allowClear'=>true],
                ],
                'filterInputOptions'=>['placeholder'=>'Pilih Dokter'],
                'value' => function($model) {
                    $d = ArrayHelper::map(Dokter::find()->joinWith('user')->where(['role'=>25])->asArray()->all(), 'user_id', 'nama');
                    return $d[$model->dpjp];
                },
                'editableOptions' => [
                    'inputType' => '\kartik\select2\Select2',
                    'options'=>
                    [
                        'data' => ArrayHelper::map(Dokter::find()->joinWith('user')->where(['role'=>25])->asArray()->all(), 'user_id', 'nama'),
                    ],
                ],
            ],
            'jam_masuk',
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
                    return (isset($model->rekamMedis[0]->rm_id)) ? 
                     Html::a('update', Url::to(['rekam-medis/update','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]), [
                            'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                            'title' => Yii::t('yii', 'Proses'),
                            'data-pjax' => '0',
                        ]) : "";  
                },
                
                'view' => function($url,$model) {
                    if(isset($model->rekamMedis[0]->rm_id))
                        // return Html::button('Lihat', [
                        //     'value'=>Url::to(['rekam-medis/view-ajax','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]),
                        //     'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                        //     'title' => Yii::t('yii', 'Lihat'),
                        //     'data-pjax' => '0',
                        // ]);
                        return Html::a('Lihat', Url::to(['rekam-medis/view','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]), [
                            'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                            'title' => Yii::t('yii', 'Lihat'),
                            'data-pjax' => '0',
                        ]);    
                    else
                        // return Html::button('Lihat', [
                        //         'value'=>$url,
                        //         'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                        //         'title' => Yii::t('yii', 'Lihat'),
                        //         'data-pjax' => '0',
                        //     ]);  
                        return Html::a('Lihat', Url::to([$url]), [
                            'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                            'title' => Yii::t('yii', 'Lihat'),
                            'data-pjax' => '0',
                        ]);  
                },
                'mutasi' => function($urls,$model) {
                    return ($model->canMutasi)? Html::button('Rujuk Internal', [
                            'value'=>Url::to(['kunjungan/mutasi','id'=>$model->kunjungan_id,'to'=>'rajal']),
                            'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                            'title' => Yii::t('yii', 'Rujuk Internal'),
                            'data-pjax' => '0',
                        ]):"";  
                }
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

