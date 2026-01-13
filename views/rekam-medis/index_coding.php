<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RekamMedisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rekam Medis Terakhir';
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
<p>
<?= Html::a('Rekap Kunjungan', Url::to(['kunjungan/rekap-kunjungan']),['target'=>'_blank','class' => 'btn btn-circle red-sunglo']) ?>

<?= Html::a('Surveilans', Url::to(['kunjungan/surveilans']),['target'=>'_blank','class' => 'btn btn-circle red-sunglo']) ?>
</p>
<div class="rekam-medis-index">

<?php Pjax::begin(); ?>    
<div class="table-responsive">
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'rm_id',
            //'user_id',
            //'kunjungan_id',
            'mr',
            [
                'attribute' => 'pasien_nama',
                'value' => 'mr0.nama'
            ],

            'dpjp.nama',
            'ruang.ruang_nm',
            'poli.medunit_nm',  
            //'tekanan_darah',
            //'nadi',
            //'respirasi_rate',
            // 'suhu',
            // 'berat_badan',
            // 'tinggi_badan',
            // 'bmi',
            // 'keluhan_utama:ntext',
            // 'anamnesis:ntext',
            // 'pemeriksaan_fisik:ntext',
            // 'hasil_penunjang:ntext',
            // 'deskripsi_tindakan:ntext',
            // 'saran_pemeriksaan:ntext',
            [
                'attribute' => 'created',
                'format' => ['date', 'php:d-F-Y'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created',
                    'clientOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ])
            ],
            // 'modified',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{coding} {view} {casemix} {update}',
            'buttons' => [
                'coding' => function($url,$model) {
                     $id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));
                     return Html::a('<span class="btn btn-default fa fa-check"></span>', Url::to(['rekam-medis/coding','id'=>$id]), [
                            'title' => Yii::t('yii', 'Coding'),
                            'data-pjax' => '0',
                        ]);
                },
                'view' => function($url,$model) {
                    $id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));

                    return Html::a('<span class="btn btn-default fa fa-eye"></span>', Url::to(['rekam-medis/view','id'=>$id]), [
                            'title' => Yii::t('yii', 'Lihat'),
                            'data-pjax' => '0',
                        ]); 
                },

                'casemix' => function($url,$model) {
                    $id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));

                    return Html::a('<span class="btn btn-default fa fa-book"></span>', Url::to(['rekam-medis/casemix','id'=>$id]), [
                            'title' => Yii::t('yii', 'Cetak Case-Mix'),
                            'data-pjax' => '0',
                        ]); 
                },
                'update' => function($url,$model) {
                     $id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));
                     // return (!$model->locked) ? Html::a('<span class="btn btn-default fa fa-pencil"></span>', Url::to(['rekam-medis/update','id'=>$id]), [
                     //        'title' => Yii::t('yii', 'Proses'),
                     //        'data-pjax' => '0',
                     //    ]) : "";

                     return Html::a('<span class="btn btn-default fa fa-pencil"></span>', Url::to(['rekam-medis/update','id'=>$id]), [
                            'title' => Yii::t('yii', 'Proses'),
                            'data-pjax' => '0',
                        ]);
                },
             ]
            ],
        ],
    ]); ?>
</div>
<?php Pjax::end(); ?></div>
