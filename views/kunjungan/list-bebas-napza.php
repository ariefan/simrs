<?php

use yii\helpers\Html;
use yii\helpers\Url ;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use dosamigos\datepicker\DatePicker;
use app\models\RekamMedis;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KunjunganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cetak Surat Keterangan Bebas Napza';

$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::a('Daftar Surat Keterangan Bebas Napza', ['rm-lab-napza/index'], ['class' => 'btn btn-success']) ?>

<div class="kunjungan-index">
<br/>

    <style type="text/css">
        .select2-container {
            z-index: 99999;
        }
    </style>
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
            'status',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{cetak}',
            'buttons' => [
            'cetak' => function($url,$model) {
                return  
                Html::a('<span class="fa fa-print"></span>', Url::to(['rm-lab-napza/create','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rekmed->rm_id, Yii::$app->params['kunciInggris'] ))]), [
                    'class' => 'btn btn-default',
                    'title' => Yii::t('yii', 'Cetak'),
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

