<?php

use yii\helpers\Html;
use yii\grid\GridView;

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Data Pasien';
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

<div class="pasien-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tambah Pasien', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'mr',
            //'klinik_id',
            'nama',
            //'identitas',
            //'no_identitas',
            // 'region_cd',
            // 'warga_negara',
            'tanggal_lahir',
            // 'jk',
             'alamat:ntext',
            // 'kode_pos',
            // 'no_telp',
            // 'pendidikan',
            // 'pekerjaan',
            // 'suku',
            // 'agama',
            // 'pj_nama',
            // 'gol_darah',
            // 'berat',
            // 'tinggi',
            // 'pj_hubungan',
            // 'pj_alamat',
            // 'pj_telpon',
            // 'nama_ayah',
            // 'nama_ibu',
            // 'email:email',
            // 'foto:ntext',
            // 'created',
            // 'modified',
            // 'user_input',
            // 'user_modified',

            //['class' => 'yii\grid\ActionColumn'],    
            ['class' => 'yii\grid\ActionColumn',
             'template' => '{update} {view} {delete} {rekap_kunjungan}',
             'buttons' => [
                'update' => function($url,$model) {
                     return Html::a('<i class="fa fa-edit"></i>', ['update','id'=>$model->mr],['title' => Yii::t('yii', 'Ubah')],['class' => 'btn btn-circle blue modalWindow']);
                },
                'view' => function($url,$model) {
                    return Html::a('<i class="fa fa-eye"></i>', ['view','id'=>$model->mr],['title' => Yii::t('yii', 'Lihat Data')] ); 
                },
                'delete' => function($url,$model) {
                     return Html::a('<i class="fa fa-trash-o"></i>', $url, [
                            'title' => Yii::t('yii', 'Hapus'),
                            'data-confirm' => Yii::t('yii', 'Apakah Anda Yakin akan menghapus Pasien ini?'),
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ]);
                },
                'rekap_kunjungan' => function($url,$model) {                
                    return Html::a('<span class="fa fa-stethoscope"></span>', ['kunjungan','id'=>$model->mr],['title' => Yii::t('yii', 'Rekap Kunjungan')]);  
                }
             ]
            ],

        ],
    ]); ?>
</div>
