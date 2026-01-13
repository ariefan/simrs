<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Laporan;
use yii\helpers\ArrayHelper;

$this->title = 'Rekap Kunjungan Rawat Inap Tahun '.$laporan->tahun;
?>

<div>
	<?php //echo Html::a('<span class="fa fa-stethoscope"> Unduh Rekap Kunjungan Tahun '.$laporan->tahun, Url::to(['laporan/unduh-rekap-kunjungan','thn'=>$laporan->tahun]), ['class' => 'btn btn-circle blue modalWindow']); ?>
</div>
<br>
<div class="rekap-kunjungan-form">
    <?php $form = ActiveForm::begin(["id"=>"form-rekap"]); ?>
    <div class="row">
      <div class="col-md-6">
        <div class="portlet light bordered">
          <label>Mencari data berdasar tahun</label>
          <input type="text" placeholder="Ketik tahun..." class="form-control" id="cari-kunjungan" name="cari-kunjungan">
          <br>
            <?php 
                if(isset($_POST["cari-kunjungan"])){
                    $tahun=$_POST["cari-kunjungan"];
                }
            ?>
        </div>    
      </div>
    </div>

    <div class="row">
		<div class="col-md-12 col-sm-12">
        	<div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-green"></i>
                        <span class="caption-subject font-green bold uppercase">
                        	<?php echo "Kunjungan Pasien ke RSUD Berau dirinci Berdasarkan jenis Kunjungan Tahun $laporan->tahun" ?>
						</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                       <table class="table table-hover table-light">
                            <thead>
                                <th rowspan="2"><strong>NO.</strong></th>
                                <th rowspan="2"><strong>KUNJUNGAN</strong></th>
                                <th colspan="3"><strong>JENIS KUNJUNGAN</strong></th>
                                <tr>
                                	<th><strong>BARU</strong></th>
                                	<th><strong>LAMA</strong></th>
                                	<th><strong>JUMLAH</strong></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Rawat Jalan dan UGD</td>
                                    <td>
                                        <?php foreach ($rajalbaru as $val_rajalbaru): ?>
                                        <?php echo $val_rajalbaru['jumlahBaru'] ?>
                                        <?php $total_val_rajalbaru=$val_rajalbaru['jumlahBaru'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($rajallama as $val_rajallama): ?>
                                        <?php echo $val_rajallama['jumlahLama'] ?>
                                        <?php $total_val_rajallama=$val_rajallama['jumlahLama'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_val_rajalbaru + $total_val_rajallama ?>                                   
                                    </td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Rawat Inap</td>
                                    <td>
                                        <?php foreach ($ranapbaru as $val_ranapbaru): ?>
                                        <?php echo $val_ranapbaru['jumlahBaru'] ?>
                                        <?php $total_val_ranapbaru=$val_ranapbaru['jumlahBaru'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($ranaplama as $val_ranaplama): ?>
                                        <?php echo $val_ranaplama['jumlahLama'] ?>
                                        <?php $total_val_ranaplama=$val_ranaplama['jumlahLama'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_val_ranapbaru + $total_val_ranaplama ?>                                   
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><H4>JUMLAH</H4></td>
                                    <td>
                                        <?php echo $total_val_rajalbaru + $total_val_ranapbaru ?> 
                                    </td>
                                    <td>
                                        <?php echo $total_val_rajallama + $total_val_ranaplama ?> 
                                    </td>
                                    <td>
                                        <?php echo $total_val_rajalbaru + $total_val_ranapbaru + $total_val_rajallama + $total_val_ranaplama ?> 
                                    </td>
                                </tr>
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
                        <span class="caption-subject font-green bold uppercase">
                            <?php echo "Daftar Ruang Rawat Inap dan Jumlah Tempat Tidur dirinci per klas perawatan" ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                       <table class="table table-hover table-light">
                            <thead>
                                <th><strong>NO.</strong></th>
                                <th><strong>RUANG RAWAT INAP</strong></th>
                                <?php foreach ($kelas as $val_kelas): ?>
                                    <th><?php echo $val_kelas['kelas_cd'] ?></th>    
                                <?php endforeach ?>
                                <th><strong>JUMLAH</strong></th>
                            </thead>

                            <tbody>
                                <?php $no=1; ?>
                                <?php foreach ($jumlahTT as $val_jumlahTT): ?>
                                <tr>
                                    <td><?php echo $no; $no++ ?></td>
                                    <td><?php echo $val_jumlahTT['bangsal_nm'];$jum=0; ?></td>
                                        <?php foreach ($kelas as $val_kelas): ?>
                                            <?php if($val_jumlahTT['kelas_cd']==$val_kelas['kelas_cd']){?>
                                            <td><?php    echo $val_jumlahTT['jumlah'];$jum=$jum+$val_jumlahTT['jumlah']; ?></td>
                                            <?php }else{ ?>
                                            <td><?php    echo "-";?></td>
                                            <?php } ?>   
                                        <?php endforeach ?>
                                    <td><?= $jum ?></td>
                                </tr>
                                <?php endforeach ?>

                            </tbody>      
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php ActiveForm::end(); ?>    
</div>