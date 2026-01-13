<?php
use app\models\Pasien;
use app\models\RekamMedis;
use yii\helpers\Html;
use yii\helpers\Url;
$rm_model = RekamMedis::find()->where(['kunjungan_id'=>$kunjungan['kunjungan_id']])->limit(1)->orderBy(['created'=>SORT_DESC])->one();



/* @var $this yii\web\View */
/* @var $model app\models\Bayar */

$this->title = 'Bayar';
$this->params['breadcrumbs'][] = ['label' => 'Bayars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <style type="text/css">
        .select2-container {
            z-index: 99999;
        }
    </style>
<div id='canvasHeader'>    
    <h6 style="text-align: center;  text-decoration: underline;">NOTA PEMBAYARAN</h6>

    <table class="table">
        <tr>
            <td>No. Invoice</td>
            <td><?= $model->no_invoice ?></td>
            <td colspan="2" align="center"><?= date('d/m/Y', strtotime($kunjungan['jam_selesai'])) ?></td>
        </tr>
        <tr>
            <td>Sudah Terima dari</td>
            <td><?= $kunjungan['mr0']['nama'] ?></td>
            <td>Dokter</td>
            <td><?= @\app\models\User::findOne($kunjungan->dpjp)->username ?></td>
        </tr>
        <tr>
            <td>No. RM</td>
            <td><?= $kunjungan['mr'] ?></td>
            <td>Poliklinik</td>
            <td><?= ($kunjungan['medunit_cd']!="")?\app\models\UnitMedis::findOne($kunjungan['medunit_cd'])->medunit_nm:"RAWAT INAP" ?></td>
        </tr>
        <tr>
            <td>Nama Pasien</td>
            <td><?= $kunjungan['mr0']['nama'] ?></td>
            <td>Tgl. Dirawat</td>
            <td><?= date('d/m/Y', strtotime($kunjungan['jam_masuk'])) ?></td>
        </tr>
       <!--  <tr>
            <td>Metode Bayar</td>
            <td><?= $model->no_invoice ?></td>
            <td>Asuransi</td>
            <td><?= $model->no_invoice ?></td>
        </tr> -->
        <tr>
            <td>Alamat</td>
            <td colspan="3"><?= $kunjungan['mr0']['alamat'] ?></td>
        </tr>
    </table>
    Dengan Rincian Sebagai Berikut:
</div>

            <div class="portlet-body form">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#tab_0" data-toggle="tab"> PEMBAYARAN </a>
                    </li>
                    <li>
                        <a href="#tab_1" data-toggle="tab"> DETAIL </a>
                    </li>
                    <li>
                        <a href="#tab_2" data-toggle="tab"> DETAIL JASA </a>
                    </li>
                    <li>
                        <a href="#tab_3" data-toggle="tab"> DETAIL SEMUA </a>
                    </li>
                    <li>
                        <a href="#tab_4" data-toggle="tab"> SINGKAT </a>
                    </li>
                    
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade in" id="tab_1">
                        <?= $this->render('bayarDetail/_form', compact('ruang','model','obat','obat_racik','tindakan','kunjungan','radiologi','lab','paket')) ?>
                    </div>
                    <div class="tab-pane fade" id="tab_2">
                        <?= $this->render('bayarDetailJasa/_form', compact('ruang','model','obat','obat_racik','tindakan','kunjungan','radiologi','lab','paket')) ?>
                    </div>
                    <div class="tab-pane fade" id="tab_3">
                        <?= $this->render('bayarDetailSemua/_form', compact('ruang','model','obat','obat_racik','tindakan','kunjungan','radiologi','lab','paket')) ?>
                    </div>
                    <div class="tab-pane fade" id="tab_4">
                    	<?= $this->render('bayarSingkat/_form', compact('ruang','model','obat','obat_racik','tindakan','kunjungan','radiologi','lab','paket')) ?>
                    </div>
                    <div class="tab-pane fade active in" id="tab_0">
                        <?= $this->render('bayarForm/_form', compact('ruang','model','obat','obat_racik','tindakan','kunjungan','radiologi','lab','paket')) ?>
                    </div>
                </div>
            </div>


<?php if ($kunjungan->eklaim!=false): ?>


    <div id="modal" class="fade modal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4>Data Eklaim</h4>
                </div>
                <div class="modal-body">
                    <div id='modalContent'></div>
                </div>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
      <div class="panel-heading">Data Integrasi E-Klaim</div>
      <div class="panel-body">
        <div class="note note-success">
                                        <h4 class="block">Info!</h4>
                                        <p> <?= $kunjungan->eklaim->statusDesc ?></p>
                                    </div>
        <div class="view-eklaim"></div>
        <?php
            $session = Yii::$app->session;
            echo Html::button('<i class="fa fa-paperclip"></i> Input Data Eklaim', [
                                'value'=>Url::to(['kunjungan-eklaim/update-all','id'=>$kunjungan->kunjungan_id,'subtotal'=>$session['subtotal']]),
                                'class'=>'btn btn-primary modalWindow',
                                'title' => Yii::t('yii', 'Input Eklaim'),
                                'data-pjax' => '0',
                            ]).' ';
            if ($kunjungan->eklaim->isComplete && ($kunjungan->eklaim->status==null || $kunjungan->eklaim->status=='')){ ?>
                <div class="btn-group">
                <button type="button" class="btn btn-warning"><i class="fa fa-cloud-upload"></i> Push Eklaim</button>
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li>
                        <?=Html::a('<i class="fa fa-magic"></i> Grouping Otomatis',Url::to(['kunjungan-eklaim/push','id'=>$kunjungan->kunjungan_id,'grouping'=>'auto']), [
                                    'title' => Yii::t('yii', 'Input Eklaim'),
                                    // 'data-pjax' => '0',
                                    'data' => [
                                        'confirm' => 'Anda yakin?',
                                    ],
                                ]); ?>
                    </li>
                    <li>
                        <?=Html::a('<i class="fa fa-wrench"></i> Grouping Manual',Url::to(['kunjungan-eklaim/push','id'=>$kunjungan->kunjungan_id,'grouping'=>'manual']), [
                                    'title' => Yii::t('yii', 'Input Eklaim'),
                                    // 'data-pjax' => '0',
                                    'data' => [
                                        'confirm' => 'Anda yakin?',
                                    ],
                                ]); ?>
                    </li>
                </ul>
            </div>
                <?php } ?>
        </div>


    </div>
    <?php 
    $url = Url::to(['kunjungan-eklaim/view','id'=>$kunjungan->kunjungan_id]);
    $script = "
        $(function(){
            $('.view-eklaim').load('$url');

            $('.modalWindow').click(function(){
                $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'))
            })
           
        });";

    $this->registerJs($script);

    ?>
<?php endIf; ?>