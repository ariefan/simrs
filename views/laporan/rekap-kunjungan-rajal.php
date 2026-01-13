<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Laporan;
use yii\helpers\ArrayHelper;

$this->title = 'Rekap Kunjungan Rawat Jalan Tahun '.$laporan->tahun;
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
            ?>
            <?php 
                echo Html::a('<span class="fa fa-stethoscope"> Tampil Rekap Kunjungan Tahun '.$tahun, Url::to(['laporan/rekap-kunjungan-rajal','thn'=>$tahun]), ['class' => 'btn btn-circle red modalWindow']); 
            ?>
            <?php    }    ?>
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
                        	<?php echo "Kunjungan Pasien ke Instalasi Rawat Jalan dan UGD RSUD Berau dirinci Berdasarkan Klinik Tujuan Tahun $laporan->tahun" ?>
						</span>
                        <span class="caption-helper"></span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                       <table class="table table-hover table-light">
                            <thead>
                                <th rowspan="2"><strong>NO.</strong></th>
                                <th rowspan="2"><strong>POLIKLINIK</strong></th>
                                <th colspan="3"><strong>JENIS KUNJUNGAN</strong></th>
                                <tr>
                                	<th><strong>BARU</strong></th>
                                	<th><strong>LAMA</strong></th>
                                	<th><strong>JUMLAH</strong></th>
                                </tr>
                            </thead>

                            <tbody>
                            	<?php $i=1; $jumBaru=0; $jumLama=0; $jumTotal=0; ?>
                            	<?php foreach ($kunjungan_klinik_tujuan as $val_kunjungan_klinik_tujuan): ?>
                                <tr>
                                    <td><?php echo $i."."; ?></td>
                                    <td><?php echo $val_kunjungan_klinik_tujuan['Nama_Unit'] ?></td>     
                                    <td><?php echo $val_kunjungan_klinik_tujuan['Baru'] ?></td>    
                                    <td><?php echo $val_kunjungan_klinik_tujuan['Lama'] ?></td>    
                                    <td><?php echo $val_kunjungan_klinik_tujuan['Total'] ?></td>   
                                    <?php $i++; ?> 
                                    <?php $jumBaru=$jumBaru+$val_kunjungan_klinik_tujuan['Baru']; ?> 
                                    <?php $jumLama=$jumLama+$val_kunjungan_klinik_tujuan['Lama']; ?>
                                    <?php $jumTotal=$jumTotal+$val_kunjungan_klinik_tujuan['Total']; ?>
                                </tr>
                                <?php endforeach ?>
                                <tr>
                                	<td></td>
                                	<td><strong><h4>JUMLAH</h4></strong></td>
                                	<td><strong><h4><?php echo $jumBaru; ?></h4></strong></td>
                                	<td><strong><h4><?php echo $jumLama; ?></h4></strong></td>
                                	<td><strong><h4><?php echo $jumTotal; ?></h4></strong></td>
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
                            <?php echo "Kunjungan Pasien ke Instalasi Rawat Jalan dan UGD RSUD dirinci Berdasarkan Kelompok umur dan penjamin Tahun $laporan->tahun" ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                       <table class="table table-hover table-light">
                            <thead>
                                <th rowspan="2"><strong>NO.</strong></th>
                                <th rowspan="2"><strong>KELOMPOK UMUR</strong></th>
                                <th colspan="7"><strong>KUNJUNGAN PASIEN RAWAT JALAN</strong></th>
                                <tr>
                                <?php foreach ($kunjungan_umur_penjamin_0_1 as $val_kunjungan_umur_penjamin_0_1): ?>
                                    <th><strong><?php echo $val_kunjungan_umur_penjamin_0_1['cara_nama'] ?></strong></th>
                                <?php endforeach ?>
                                    <th><strong>Jumlah</strong></th>
                                </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>1.</td>
                                <td>0 - <1 tahun</td>
                                <?php $jumlah1=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_0_1 as $val_kunjungan_umur_penjamin_0_1): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_0_1['Jumlah'] ?></td>
                                    <?php $jumlah1=$jumlah1+$val_kunjungan_umur_penjamin_0_1['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah1 ?></td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>1 - 4 tahun</td>
                                <?php $jumlah2=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_1_4 as $val_kunjungan_umur_penjamin_1_4): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_1_4['Jumlah'] ?></td>
                                    <?php $jumlah2=$jumlah2+$val_kunjungan_umur_penjamin_1_4['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah2 ?></td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>5 - 14 tahun</td>
                                <?php $jumlah3=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_5_14 as $val_kunjungan_umur_penjamin_5_14): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_5_14['Jumlah'] ?></td>
                                    <?php $jumlah3=$jumlah3+$val_kunjungan_umur_penjamin_5_14['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah3 ?></td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>15 - 24 tahun</td>
                                <?php $jumlah4=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_15_24 as $val_kunjungan_umur_penjamin_15_24): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_15_24['Jumlah'] ?></td>
                                    <?php $jumlah4=$jumlah4+$val_kunjungan_umur_penjamin_15_24['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah4 ?></td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>25 - 44 tahun</td>
                                <?php $jumlah5=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_25_44 as $val_kunjungan_umur_penjamin_25_44): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_25_44['Jumlah'] ?></td>
                                    <?php $jumlah5=$jumlah5+$val_kunjungan_umur_penjamin_25_44['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah5 ?></td>
                            </tr>
                            <tr>
                                <td>6.</td>
                                <td>45 - 64 tahun</td>
                                <?php $jumlah6=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_45_64 as $val_kunjungan_umur_penjamin_45_64): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_45_64['Jumlah'] ?></td>
                                    <?php $jumlah6=$jumlah6+$val_kunjungan_umur_penjamin_45_64['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah6 ?></td>
                            </tr>
                            <tr>
                                <td>7.</td>
                                <td>>= 65 tahun</td>
                                <?php $jumlah7=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_65 as $val_kunjungan_umur_penjamin_65): ?>
                                    <td><?php echo $val_kunjungan_umur_penjamin_65['Jumlah'] ?></td>
                                    <?php $jumlah7=$jumlah7+$val_kunjungan_umur_penjamin_65['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><?php echo $jumlah7 ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><STRONG><H4>JUMLAH</H4></STRONG></td>
                                <?php $jumlah8=0; ?>
                                <?php foreach ($kunjungan_umur_penjamin_total as $val_kunjungan_umur_penjamin_total): ?>
                                    <td><STRONG><H4><?php echo $val_kunjungan_umur_penjamin_total['Jumlah'] ?></H4></STRONG></td>
                                    <?php $jumlah8=$jumlah8+$val_kunjungan_umur_penjamin_total['Jumlah']; ?>
                                <?php endforeach ?>
                                <td><STRONG><H4><?php echo $jumlah8 ?></H4></STRONG></td>
                            </tr>
                            </tbody>      
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php ActiveForm::end(); ?>    
</div>