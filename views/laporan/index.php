<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use miloschuman\highcharts\Highcharts;
use app\models\Laporan;

/* @var $this yii\web\View */

$this->title = 'Profil Rumah Sakit';
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


<div class="row">

    <div class="col-md-12">
        <div class="portlet light bordered">
            
            <br/>
            <div class="portlet-body form">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#tab_1" data-toggle="tab"> KUNJUNGAN </a>
                    </li>
                    <li>
                        <a href="#tab_2" data-toggle="tab"> KINERJA </a>
                    </li>
                    <li>
                        <a href="#tab_3" data-toggle="tab"> MEDIK </a>
                    </li>
                    <li>
                        <a href="#tab_4" data-toggle="tab"> HEMODIALISA </a>
                    </li>
                    <li>
                        <a href="#tab_5" data-toggle="tab"> PARTUS </a>
                    </li>
                    <li>
                        <a href="#tab_6" data-toggle="tab"> PENUNJANG </a>
                    </li>
                    <li>
                        <a href="#tab_7" data-toggle="tab"> FARMASI </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <br/>
            <div class="portlet-body form">
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="tab_1">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <i class="icon-bar-chart font-blue"> KUNJUNGAN </i>
                                    <th><h5><STRONG>BIDANG LAPORAN</STRONG></h5></th>
                                    <th><h5><STRONG>AKSI</STRONG></h5></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><ul>1. Rekap Pengunjung</ul></td>
                                        <td>

                                            <?= 
                                            //Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>$laporan->getTahunDefault()]), 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>$laporan->getTahunDefault()]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>2. Rekap Kunjungan Rawat Jalan</ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan-rajal','thn'=>$laporan->getTahunDefault()]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>3. Rekap Kunjungan Rawat Inap</ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan-ranap','thn'=>$laporan->getTahunDefault()]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>4. Rekap Kunjungan SHRI (Sensus Harian Rawat Inap)</ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan-shri','thn'=>$laporan->getTahunDefault()]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <div class="col-lg-6 col-xs-1 col-sm-1" >
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject bold uppercase font-dark">Kunjungan berdasar alasan berkunjung</span>
                                                <span class="caption-helper">Tahun Ini</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <?php
                                                echo Highcharts::widget([
                                                   'options' => [
                                                      'chart' => ['type'=>'pie'],
                                                      'title' => ['text' => 'Tahun Ini'],
                                                      'tooltip' => ['pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'],
                                                      'plotOptions'=> [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor'=> 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled'=> false
                                                                    ],
                                                                    'showInLegend'=> true
                                                                ]
                                                            ],
                                                      
                                                      'series' => [
                                                         [
                                                         'name' => 'Alasan kunjungan', 
                                                         'colorByPoint' => true,
                                                         'data' => $alasan_kunjungan_tahun_ini
                                                         ],
                                                      ]
                                                   ]
                                                ]);
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xs-1 col-sm-1" >
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject bold uppercase font-dark">Kunjungan berdasar tipe kunjungan</span>
                                                <span class="caption-helper">Tahun Ini</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <?php
                                                echo Highcharts::widget([
                                                   'options' => [
                                                      'chart' => ['type'=>'pie'],
                                                      'title' => ['text' => 'Tahun Ini'],
                                                      'tooltip' => ['pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'],
                                                      'plotOptions'=> [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor'=> 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled'=> false
                                                                    ],
                                                                    'showInLegend'=> true
                                                                ]
                                                            ],
                                                      
                                                      'series' => [
                                                         [
                                                         'name' => 'Alasan kunjungan', 
                                                         'colorByPoint' => true,
                                                         'data' => $tipe_kunjungan_tahun_ini
                                                         ],
                                                      ]
                                                   ]
                                                ]);
                                                ?>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="tab_2">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <i class="icon-bar-chart font-blue"> KINERJA </i>
                                    <th><h5><STRONG>BIDANG LAPORAN</STRONG></h5></th>
                                    <th><h5><STRONG>AKSI</STRONG></h5></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><ul>1. BOR (Bed Occupancy Rate) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>$laporan->getTahunDefault()]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>2. aLOS </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>3. TOI (Turn Over Interval) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>4. BTO (Bed Turn Over) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>5. GDR (Gross Death Rate) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>6. NDR (Net Death Rate) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <div class="col-lg-12 col-xs-1 col-sm-1" >
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject bold uppercase font-dark"> Perbandingan Jumlah Tempat Tidur per Bangsal</span>
                                                <span class="caption-helper">Tahun ini</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <?php
                                                echo Highcharts::widget([
                                                   'options' => [
                                                      'chart' => ['type'=>'pie'],
                                                      'title' => ['text' => 'Tahun Ini'],
                                                      'tooltip' => ['pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'],
                                                      'plotOptions'=> [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor'=> 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled'=> false
                                                                    ],
                                                                    'showInLegend'=> true
                                                                ]
                                                            ],
                                                      
                                                      'series' => [
                                                         [
                                                         'name' => 'Jumlah Tempat Tidur', 
                                                         'colorByPoint' => true,
                                                         'data' => $ruang_ranap
                                                         ],
                                                      ]
                                                   ]
                                                ]);
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xs-6 col-sm-6">
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject bold uppercase font-dark">Jumlah Tempat Tidur per Bangsal</span>
                                                <span class="caption-helper"></span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <?php
                                                echo Highcharts::widget([
                                                   'options' => [
                                                      'chart' => ['type'=>'column'],
                                                      'title' => ['text' => 'Saat Ini'],
                                                      'xAxis' => [
                                                         'categories' => ['Anggrek','Bakung','Cempaka','Dahlia','ICU','Kana','Mawar','Melati','Teratai','VK']
                                                      ],
                                                      'yAxis' => [
                                                         'title' => ['text' => 'Jumlah Tempat Tidur']
                                                      ],
                                                      'series' => [
                                                         ['name' => 'Tipe Unit Medis', 'data' => $ruang_ranap],
                                                      ]
                                                   ]
                                                ]);
                                                ?>
                                        </div>
                                    </div>
                                </div>                                
                        </div>                        
                    </div>

                    <div class="more_adv tab-pane fade" id="tab_3">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <i class="icon-bar-chart font-blue"> MEDIK </i>
                                    <th><h5><STRONG>BIDANG LAPORAN</STRONG></h5></th>
                                    <th><h5><STRONG>AKSI</STRONG></h5></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><ul>1. Jumlah tindakan pertahun </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>'2017']), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>2. Tindakan di Instalasi Rawat Jalan </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>3. Tindakan medik di Instalasi rawat inap dirinci per unit perawatan </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>4. Tindakan medik di Instalasi Bedah Sentral </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>5. Tindakan / asuhan Keperawatan dan asuhan kebidanan </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                    <div class="more_adv tab-pane fade" id="tab_4">

                    </div>
                    <div class="more_adv tab-pane fade" id="tab_5">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <i class="icon-bar-chart font-blue"> PARTUS </i>
                                    <th><h5><STRONG>BIDANG LAPORAN</STRONG></h5></th>
                                    <th><h5><STRONG>AKSI</STRONG></h5></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><ul>1. Jumlah dan jenis persalinan pertahun </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>'2017']), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>2. Umur Ibu bersalin </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>3. Diagnosa saat masuk persalinan (alasan) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>4. Tindak Lanjut Penanganan ibu Bersalin </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>5. Manjemen Gynekologi dan Obstetric Lainnya </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>6. Rujukan Obstetrik dan Gynekologi ke RSUD Wonosari </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>7. Kondisi Bayi Baru Lahir yang lahir di RS </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>     
                    </div>

                    <div class="more_adv tab-pane fade" id="tab_6">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <i class="icon-bar-chart font-blue"> PENUNJANG </i>
                                    <th><h5><STRONG>BIDANG LAPORAN</STRONG></h5></th>
                                    <th><h5><STRONG>AKSI</STRONG></h5></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><ul>1. LABORATORIUM </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>'2017']), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>2. RADIOLOGI </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>3. ELEKTROMEDIK </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                                <div class="col-lg-12 col-xs-6 col-sm-6">
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject bold uppercase font-dark">Unit Medis Penunjang</span>
                                                <span class="caption-helper"></span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <?php
                                                echo Highcharts::widget([
                                                   'options' => [
                                                      'chart' => ['type'=>'column'],
                                                      'title' => ['text' => 'Saat Ini'],
                                                      'xAxis' => [
                                                         'categories' => ['Poliklinik','Radiologi','Laboratorium']
                                                      ],
                                                      'yAxis' => [
                                                         'title' => ['text' => 'Jumlah Fasilitas']
                                                      ],
                                                      'series' => [
                                                         ['name' => 'Tipe Unit Medis', 'data' => $unit_medis],
                                                      ]
                                                   ]
                                                ]);
                                                ?>
                                        </div>
                                    </div>
                                </div>

                        </div> 
                    </div>

                    <div class="more_adv tab-pane fade" id="tab_7">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <i class="icon-bar-chart font-blue"> FARMASI </i>
                                    <th><h5><STRONG>BIDANG LAPORAN</STRONG></h5></th>
                                    <th><h5><STRONG>AKSI</STRONG></h5></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><ul>1. Penulisan Resep </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-pengunjung','thn'=>'2017']), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>2. Pengadaan Obat </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>3. Resep Masuk dan yang dapat terlayani per bulan*) </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><ul>4. Cara Bayar </ul></td>
                                        <td>
                                            <?= 
                                            Html::a('<span class="fa fa-stethoscope"></span> Tampil Detail', Url::to(['laporan/rekap-kunjungan',]), 
                                                [
                                                    'title' => Yii::t('yii', 'Proses'),
                                                    'class' => 'btn green',
                                                ]);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                                <div class="col-lg-12 col-xs-1 col-sm-1" >
                                    <div class="portlet light bordered">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <span class="caption-subject bold uppercase font-dark"> Perbandingan Cara Bayar </span>
                                                <span class="caption-helper">Tahun ini</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <?php
                                                echo Highcharts::widget([
                                                   'options' => [
                                                      'chart' => ['type'=>'pie'],
                                                      'title' => ['text' => 'Tahun Ini'],
                                                      'tooltip' => ['pointFormat'=>'{series.name}: <b>{point.percentage:.1f}%</b>'],
                                                      'plotOptions'=> [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor'=> 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled'=> false
                                                                    ],
                                                                    'showInLegend'=> true
                                                                ]
                                                            ],
                                                      
                                                      'series' => [
                                                         [
                                                         'name' => 'Prosentase cara bayar', 
                                                         'colorByPoint' => true,
                                                         'data' => $cara_bayar
                                                         ],
                                                      ]
                                                   ]
                                                ]);
                                                ?>
                                        </div>
                                    </div>
                                </div>

                        </div> 
                    </div>
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

