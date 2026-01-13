<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\RmInapGiziSeach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permintaan Diet';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-inap-gizi-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

              [
                'attribute'=>'rm_id',
                'value'=>'rekamMedis.mr'
            ],
             [
                'attribute'=>'mr',
                'value'=>'mr.nama'
            ],
           
            [
                'attribute'=>'kunjungan_id',
                'value'=>'ruang.ruang_cd'
            ],
            [
                'attribute'=>'kode_diet',
                'value'=>'diet.nama_diet'
            ],
            'jam_makan',
            'jam_makan_spesifik',
            'diagnosa',
            'status',
            'created',

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{terproses} {batal-terproses}',
             'buttons' => [
                'terproses' => function($url,$model) {
                    return ($model->status=='Belum Diproses') ? Html::a('<span class="fa fa-check"></span>', $url, [
                            'title' => Yii::t('yii', 'Terproses'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'terproses?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },
                'batal-terproses' => function($url,$model) {
                    return ($model->status=='Sudah Diproses') ? Html::a('<span class="fa fa-undo"></span>', $url, [
                            'title' => Yii::t('yii', 'Batal Terproses'),
                            'class' => 'btn btn-default',
                            'data-confirm' => Yii::t('yii', 'Batal Terproses?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]) : "";  
                },


              ]
            ],
        ],
    ]); ?>
</div>
