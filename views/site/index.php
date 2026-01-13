<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */

$this->title = 'Rekam Medis';
?>

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

<?php if(!$complete_profile): ?>
<div class="alert alert-block alert-danger fade in">
    <h4 class="alert-heading">Lengkapi Profil Dokter!</h4>
    <p> Harap Lengkapi Profil Dokter </p>
    <br/>
    <p>
        <?= Html::a('Lengkapi',Url::to(['dokter/update','id'=>Yii::$app->user->identity->id]),['class'=>'btn red']) ?>
        <?= Html::a('Bantuan',Url::to(['site/bantuan']),['class'=>'btn blue']) ?> 
    </p>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-red"></i>
                    <span class="caption-subject font-red bold uppercase">Antrian Pemeriksaan</span>
                    <span class="caption-helper"></span>
                </div>
                <div class="actions">
                    <?= Html::a('Pasien Lama',Url::to(['kunjungan/create']),['class'=>'btn btn-circle green modalWindow']) ?> 
                    <?= Html::a('Pasien Baru',Url::to(['pasien/create']),['class'=>'btn btn-circle red-sunglo modalWindow']) ?> 
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th>Kode Pasien</th>
                            <th>Nama</th>
                            <th>Waktu Periksa</th>
                            <th>Unit Medis</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach($kunjungan as $val): ?>
                                <tr>
                                    <td><?= $val['mr'] ?></td>
                                    <td><?= $val['mr0']['nama'] ?></td>
                                    <td><?= $val['jam_masuk'] ?></td>
                                    <td><?= $val['medunit_cd']?></td>
                                    <td><?= $val['status'] ?></td>
                                    
                                    <td>
                                        <?= 
                                        $val['status'] == 'antri' ?
                                        Html::a('<span class="fa fa-stethoscope"></span> Proses', Url::to(['rekam-medis/create','kunjungan_id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['kunjungan_id'], Yii::$app->params['kunciInggris'] ))]), [
                                                'title' => Yii::t('yii', 'Proses'),
                                                'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                                            ]) : 
                                        Html::a('<span class="fa fa-stethoscope"></span> Proses', Url::to(['rekam-medis/update','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['rekamMedis'][0]['rm_id'], Yii::$app->params['kunciInggris'] ))]), [
                                                'title' => Yii::t('yii', 'Proses'),
                                                'class' => 'btn dark btn-sm btn-outline sbold uppercase',
                                            ]);
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-blue"></i>
                    <span class="caption-subject font-blue bold uppercase">Antrian Farmasi</span>
                    <span class="caption-helper"></span>
                </div>
                
            </div>
            <div class="portlet-body">
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th>MR</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach($farmasi as $val): ?>
                                <tr>
                                    <td><?= $val['mr'] ?></td>
                                    <td><?= $val['mr0']['nama'] ?></td>
                                    <td><?= $val['mr0']['alamat'] ?></td>
                                    <td>
                                        <?= Html::a(
                                            '<i class="fa fa-check"></i>',
                                            Url::to(['rekam-medis/check-obat','kunjungan_id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['kunjungan_id'], Yii::$app->params['kunciInggris'] )),'asal'=>'site/index']),
                                            ['class'=>'btn dark btn-sm btn-outline sbold uppercase',
                                                'title' => Yii::t('yii', 'Lihat')]
                                            ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Antrian Pembayaran</span>
                    <span class="caption-helper"></span>
                </div>
                
            </div>
            <div class="portlet-body">
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th>MR</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach($pembayaran as $val): ?>
                                <tr>
                                    <td><?= $val['mr'] ?></td>
                                    <td><?= $val['mr0']['nama'] ?></td>
                                    <td><?= $val['mr0']['alamat'] ?></td>
                                    <td>

                                        <?php 
                                        //echo Html::button('Bayar', ['value'=>Url::to(['bayar/create','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['kunjungan_id'], Yii::$app->params['kunciInggris'] )),'asal'=>'site/index']),'class' => 'btn dark btn-sm btn-outline sbold uppercase modalWindow']);
                                        echo Html::a('Bayar', Url::to(['bayar/create','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['kunjungan_id'], Yii::$app->params['kunciInggris'] )),'asal'=>'site/index']),['class' => 'btn dark btn-sm btn-outline sbold uppercase']);

                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-green"></i>
                    <span class="caption-subject font-green bold uppercase">Pasien Selesai</span>
                    <span class="caption-helper">5 Terakhir</span>
                </div>
                
            </div>
            <div class="portlet-body">
                <div class="table-scrollable table-scrollable-borderless">
                    <table class="table table-hover table-light">
                        <thead>
                            <th>MR</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>
                            <?php foreach($selesai as $val): ?>
                                <tr>
                                    <td><?= $val['mr'] ?></td>
                                    <td><?= $val['mr0']['nama'] ?></td>
                                    <td><?= $val['mr0']['alamat'] ?></td>
                                    <td>
                                        <?= Html::button('Invoice', ['value'=>Url::to(['bayar/view','id'=>$val['bayar'][0]['no_invoice'],'asal'=>'site/index']),'class' => 'btn dark btn-sm btn-outline sbold uppercase modalWindow']) ?>
                                        <?php 

                                        $id = utf8_encode(Yii::$app->security->encryptByKey( $val['rekamMedis'][0]['rm_id'], Yii::$app->params['kunciInggris'] ));

                                        echo Html::a('RM', Url::to(['rekam-medis/view','id'=>$id]), [
                                                'title' => Yii::t('yii', 'Lihat'),
                                                'class' => "btn dark btn-sm btn-outline sbold uppercase"
                                            ]); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading">Pasien Bulan Ini</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-green icon-users"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Pasien</span>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?= $pasien_bulan ?>"><?= $pasien_bulan ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
            <h4 class="widget-thumb-heading">Pasien Hari Ini</h4>
            <div class="widget-thumb-wrap">
                <i class="widget-thumb-icon bg-red icon-user"></i>
                <div class="widget-thumb-body">
                    <span class="widget-thumb-subtitle">Pasien</span>
                    <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?= $pasien ?>"><?= $pasien ?></span>
                </div>
            </div>
        </div>
    </div>
    
</div>


<?php

$script = <<< JS
    $(function(){
        $('.modalWindow').click(function(){
            $('#kartik-modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'))
        })

         var time = new Date().getTime();
         $(document.body).bind("mousemove keypress", function(e) {
             time = new Date().getTime();
         });

         function refresh() {
             if(new Date().getTime() - time >= 60000) 
                 window.location.reload(true);
             else 
                 setTimeout(refresh, 10000);
         }

         setTimeout(refresh, 10000);
    });

JS;

$this->registerJs($script); 
?>

