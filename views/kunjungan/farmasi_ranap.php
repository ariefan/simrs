<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Farmasi Rawat Inap';
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
                'attribute' => 'ruang_cd',
                'value' => 'ruang.ruang_nm'
            ],

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
            //'jam_selesai',
            //'status',
            // 'created',
            // 'user_input',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{edit} {view}',

             'buttons' => [
                // 'edit' => function($url,$model) {
                //     $id = utf8_encode(Yii::$app->security->encryptByKey( $model->kunjungan_id, Yii::$app->params['kunciInggris'] ));
                //     if(isset($model->rekamMedis[0]->rm_id))
                //     return Html::a('Edit', Url::to(['rekam-medis/check-obat','kunjungan_id'=>$id,'asal'=>'kunjungan/farmasi']), [
                //             'class'=>'btn dark btn-sm btn-outline sbold uppercase',
                //             'title' => Yii::t('yii', 'Edit Obat'),
                //             'data-pjax' => '0',
                //             'target' => '_blank'
                //         ]); 
                // },
                'view' => function($url,$model) {
                    if(isset($model->rm_id))
                    return Html::button('Lihat', [
                            'value'=>Url::to(['rekam-medis/cetak-resep','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] )),'asal'=>'kunjungan/farmasi']),
                            'class'=>'btn dark btn-sm btn-outline sbold uppercase modalWindow',
                            'title' => Yii::t('yii', 'Lihat'),
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

