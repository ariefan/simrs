<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model app\models\Pasien */


use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\MetronicAsset;
use app\models\Menu;

$this->title = "RM Pasien: ".$model->mr;
$this->params['breadcrumbs'][] = ['label' => 'Pasien', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/metronic/pages/css/profile.min.css',['depends'=>'app\assets\MetronicAsset']);
?>

<?php if(Yii::$app->session->getFlash('success')): ?>
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
    <?= Yii::$app->session->getFlash('success'); ?>
    <?= Html::a(', Pasien Berikutnya ',Url::to(['site/index'])) ?>
</div>
<?php endif; ?>
<?php
    Modal::begin([
            'header' => '<h4>Pasien</h4>',
            'id' => 'modal',
        ]);

    echo "<div id='modalContent'></div>";

    Modal::end();

?>

<div class="pasien-view">
    <p>
        <?php if(Yii::$app->user->identity->role==10 || Yii::$app->user->identity->role==11){
        echo Html::a('Ubah', ['update', 'id' => $model->mr], ['class' => 'btn btn-circle blue']); 
        echo Html::a('Hapus', ['delete', 'id' => $model->mr], [
            'class' => 'btn btn-circle red',
            'data' => [
                'confirm' => 'Benarkan Anda akan menghapus data ini ?',
                'method' => 'post',
            ],
        ]); 
        echo Html::a('Cetak Kartu', ['kartu', 'id' => $model->mr], ['class' => 'btn btn-circle green']);
    } 
        echo Html::a('Riwayat Pasien', ['pasien/kunjungan','id'=>$model->mr], ['class' => 'btn btn-circle btn-primary']); 
     ?>
    </p>

<h1>RM: <?= $model->mr ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mr',
            // 'klinik_id',
            'nama',
            'tanggal_lahir',
            'jk',
            'umur',
            'no_asuransi',
            'jenis_asuransi',
            'identitas',
            'no_identitas',
            'region_cd',
            'warga_negara',
            'alamat:ntext',
            'kode_pos',
            'no_telp',
            'pendidikan',
            'pekerjaan',
            'suku',
            'agama',
            'pj_nama',
            'gol_darah',
            'berat',
            'tinggi',
            'pj_hubungan',
            'pj_alamat',
            'pj_telpon',
            'nama_ayah',
            'nama_ibu',
            'email:email',
            'foto:ntext',
            'created',
            'modified',
            'user_input',
            'user_modified',
        ],
    ]) ?>

</div>
