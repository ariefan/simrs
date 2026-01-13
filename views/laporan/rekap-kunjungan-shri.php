<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Laporan;
use yii\helpers\ArrayHelper;

$this->title = 'Sensus Harian Rawat Inap Tahun '.$laporan->tahun;
?>

<div>
    <?php //echo Html::a('<span class="fa fa-stethoscope"> Unduh Rekap Kunjungan Tahun '.$laporan->tahun, Url::to(['laporan/unduh-rekap-kunjungan','thn'=>$laporan->tahun]), ['class' => 'btn btn-circle blue modalWindow']); ?>
</div>
<br>
<div class="rekap-shri-form">
    <?php $form = ActiveForm::begin(["id"=>"form-rekap"]); ?>
    <div class="row">
      <div class="col-md-6">
        <div class="portlet light bordered">
          <label>Mencari data berdasar tahun</label>
          <input type="text" placeholder="Ketik tahun..." class="form-control" id="cari-rekap" name="cari-rekap">
          <br>
            <?php 
                if(isset($_POST["cari-rekap"])){
                    $tahun=$_POST["cari-rekap"];
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
                            <?php echo "Sensus Harian Rawat Inap RSUD BERAU Tahun $laporan->tahun" ?>
                        </span>
                        <span class="caption-helper"></span>
                    </div>
                </div>

                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                       <table class="table table-hover table-light">
                            <thead>
                                <th rowspan="2"><strong>NO.</strong></th>
                                <th rowspan="2"><strong>BULAN</strong></th>
                                <th rowspan="2"><strong>PASIEN AWAL</strong></th>
                                <th rowspan="2"><strong>PASIEN MASUK</strong></th>
                                <th rowspan="2"><strong>PASIEN DIRAWAT</strong></th>
                                <th rowspan="2"><strong>PASIEN KELUAR HIDUP</strong></th>
                                <th colspan="2"><strong>PASIEN KELUAR MATI</strong></th>
                                <th rowspan="2"><strong>JUMLAH PASIEN KELUAR</strong></th>
                                <th rowspan="2"><strong>LAMA PERAWATAN</strong></th>
                                <th rowspan="2"><strong>JUMLAH HARI PERAWATAN</strong></th>
                                <th rowspan="2"><strong>SISA PASIEN DIRAWAT</strong></th>
                                <tr>
                                    <th><strong> < 48 JAM</strong></th>
                                    <th><strong> >= 48 JAM</strong></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td>Januari</td>
                                    <td>
                                        <?php foreach ($pasien_awal_januari as $val_pasien_awal_januari): ?>
                                        <?php echo $val_pasien_awal_januari['jumlah'] ?>
                                        <?php $total_januari=$val_pasien_awal_januari['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_januari as $val_pasien_masuk_januari): ?>
                                        <?php echo $val_pasien_masuk_januari['jumlah'] ?>
                                        <?php $total_januari=$total_januari+$val_pasien_masuk_januari['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_januari; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_januari as $val_pasien_keluarhidup_januari): ?>
                                        <?php echo $val_pasien_keluarhidup_januari['jumlah'] ?>
                                        <?php $pasien_keluar_januari=$val_pasien_keluarhidup_januari['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_januari as $val_pasien_mati_januari): ?>
                                        <?php echo $val_pasien_mati_januari['jumlah'] ?>
                                        <?php $pasien_keluar_januari=$pasien_keluar_januari+$val_pasien_mati_januari['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_januari as $val_pasien_mati2_januari): ?>
                                        <?php echo $val_pasien_mati2_januari['jumlah'] ?>
                                        <?php $pasien_keluar_januari=$pasien_keluar_januari+$val_pasien_mati2_januari['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_januari; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==1){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_januari-$pasien_keluar_januari; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2.</td>
                                    <td>Februari</td>
                                    <td>
                                        <?php foreach ($pasien_awal_februari as $val_pasien_awal_februari): ?>
                                        <?php echo $val_pasien_awal_februari['jumlah'] ?>
                                        <?php $total_februari=$val_pasien_awal_februari['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_februari as $val_pasien_masuk_februari): ?>
                                        <?php echo $val_pasien_masuk_februari['jumlah'] ?>
                                        <?php $total_februari=$total_februari+$val_pasien_masuk_februari['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_februari; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_februari as $val_pasien_keluarhidup_februari): ?>
                                        <?php echo $val_pasien_keluarhidup_februari['jumlah'] ?>
                                        <?php $pasien_keluar_februari=$val_pasien_keluarhidup_februari['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_februari as $val_pasien_mati_februari): ?>
                                        <?php echo $val_pasien_mati_februari['jumlah'] ?>
                                        <?php $pasien_keluar_februari=$pasien_keluar_februari+$val_pasien_mati_februari['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_februari as $val_pasien_mati2_februari): ?>
                                        <?php echo $val_pasien_mati2_februari['jumlah'] ?>
                                        <?php $pasien_keluar_februari=$pasien_keluar_februari+$val_pasien_mati2_februari['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_februari; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==2){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>  
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_februari-$pasien_keluar_februari; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3.</td>
                                    <td>Maret</td>
                                    <td>
                                        <?php foreach ($pasien_awal_maret as $val_pasien_awal_maret): ?>
                                        <?php echo $val_pasien_awal_maret['jumlah'] ?>
                                        <?php $total_maret=$val_pasien_awal_maret['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_maret as $val_pasien_masuk_maret): ?>
                                        <?php echo $val_pasien_masuk_maret['jumlah'] ?>
                                        <?php $total_maret=$total_maret+$val_pasien_masuk_maret['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_maret; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_maret as $val_pasien_keluarhidup_maret): ?>
                                        <?php echo $val_pasien_keluarhidup_maret['jumlah'] ?>
                                        <?php $pasien_keluar_maret=$val_pasien_keluarhidup_maret['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_maret as $val_pasien_mati_maret): ?>
                                        <?php echo $val_pasien_mati_maret['jumlah'] ?>
                                        <?php $pasien_keluar_maret=$pasien_keluar_maret+$val_pasien_mati_maret['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_maret as $val_pasien_mati2_maret): ?>
                                        <?php echo $val_pasien_mati2_maret['jumlah'] ?>
                                        <?php $pasien_keluar_maret=$pasien_keluar_maret+$val_pasien_mati2_maret['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_maret; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==3){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?> 
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_maret-$pasien_keluar_maret; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4.</td>
                                    <td>April</td>
                                    <td>
                                        <?php foreach ($pasien_awal_april as $val_pasien_awal_april): ?>
                                        <?php echo $val_pasien_awal_april['jumlah'] ?>
                                        <?php $total_april=$val_pasien_awal_april['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_april as $val_pasien_masuk_april): ?>
                                        <?php echo $val_pasien_masuk_april['jumlah'] ?>
                                        <?php $total_april=$total_april+$val_pasien_masuk_april['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_april; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_april as $val_pasien_keluarhidup_april): ?>
                                        <?php echo $val_pasien_keluarhidup_april['jumlah'] ?>
                                        <?php $pasien_keluar_april=$val_pasien_keluarhidup_april['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_april as $val_pasien_mati_april): ?>
                                        <?php echo $val_pasien_mati_april['jumlah'] ?>
                                        <?php $pasien_keluar_april=$pasien_keluar_april+$val_pasien_mati_april['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_april as $val_pasien_mati2_april): ?>
                                        <?php echo $val_pasien_mati2_april['jumlah'] ?>
                                        <?php $pasien_keluar_april=$pasien_keluar_april+$val_pasien_mati2_april['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_april; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==4){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_april-$pasien_keluar_april; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5.</td>
                                    <td>Mei</td>
                                    <td>
                                        <?php foreach ($pasien_awal_mei as $val_pasien_awal_mei): ?>
                                        <?php echo $val_pasien_awal_mei['jumlah'] ?>
                                        <?php $total_mei=$val_pasien_awal_mei['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_mei as $val_pasien_masuk_mei): ?>
                                        <?php echo $val_pasien_masuk_mei['jumlah'] ?>
                                        <?php $total_mei=$total_mei+$val_pasien_masuk_mei['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_mei; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_mei as $val_pasien_keluarhidup_mei): ?>
                                        <?php echo $val_pasien_keluarhidup_mei['jumlah'] ?>
                                        <?php $pasien_keluar_mei=$val_pasien_keluarhidup_mei['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_mei as $val_pasien_mati_mei): ?>
                                        <?php echo $val_pasien_mati_mei['jumlah'] ?>
                                        <?php $pasien_keluar_mei=$pasien_keluar_mei+$val_pasien_mati_mei['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_mei as $val_pasien_mati2_mei): ?>
                                        <?php echo $val_pasien_mati2_mei['jumlah'] ?>
                                        <?php $pasien_keluar_mei=$pasien_keluar_mei+$val_pasien_mati2_mei['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_mei; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==5){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_mei-$pasien_keluar_mei; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6.</td>
                                    <td>Juni</td>
                                    <td>
                                        <?php foreach ($pasien_awal_juni as $val_pasien_awal_juni): ?>
                                        <?php echo $val_pasien_awal_juni['jumlah'] ?>
                                        <?php $total_juni=$val_pasien_awal_juni['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_juni as $val_pasien_masuk_juni): ?>
                                        <?php echo $val_pasien_masuk_juni['jumlah'] ?>
                                        <?php $total_juni=$total_juni+$val_pasien_masuk_juni['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_juni; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_juni as $val_pasien_keluarhidup_juni): ?>
                                        <?php echo $val_pasien_keluarhidup_juni['jumlah'] ?>
                                        <?php $pasien_keluar_juni=$val_pasien_keluarhidup_juni['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_juni as $val_pasien_mati_juni): ?>
                                        <?php echo $val_pasien_mati_juni['jumlah'] ?>
                                        <?php $pasien_keluar_juni=$pasien_keluar_juni+$val_pasien_mati_juni['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_juni as $val_pasien_mati2_juni): ?>
                                        <?php echo $val_pasien_mati2_juni['jumlah'] ?>
                                        <?php $pasien_keluar_juni=$pasien_keluar_juni+$val_pasien_mati2_juni['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_juni; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==6){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_juni-$pasien_keluar_juni; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7.</td>
                                    <td>Juli</td>
                                    <td>
                                        <?php foreach ($pasien_awal_juli as $val_pasien_awal_juli): ?>
                                        <?php echo $val_pasien_awal_juli['jumlah'] ?>
                                        <?php $total_juli=$val_pasien_awal_juli['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_juli as $val_pasien_masuk_juli): ?>
                                        <?php echo $val_pasien_masuk_juli['jumlah'] ?>
                                        <?php $total_juli=$total_juli+$val_pasien_masuk_juli['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_juli; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_juli as $val_pasien_keluarhidup_juli): ?>
                                        <?php echo $val_pasien_keluarhidup_juli['jumlah'] ?>
                                        <?php $pasien_keluar_juli=$val_pasien_keluarhidup_juli['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_juli as $val_pasien_mati_juli): ?>
                                        <?php echo $val_pasien_mati_juli['jumlah'] ?>
                                        <?php $pasien_keluar_juli=$pasien_keluar_juli+$val_pasien_mati_juli['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_juli as $val_pasien_mati2_juli): ?>
                                        <?php echo $val_pasien_mati2_juli['jumlah'] ?>
                                        <?php $pasien_keluar_juli=$pasien_keluar_juli+$val_pasien_mati2_juli['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_juli; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==7){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_juli-$pasien_keluar_juli; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>8.</td>
                                    <td>Agustus</td>
                                    <td>
                                        <?php foreach ($pasien_awal_agustus as $val_pasien_awal_agustus): ?>
                                        <?php echo $val_pasien_awal_agustus['jumlah'] ?>
                                        <?php $total_agustus=$val_pasien_awal_agustus['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_agustus as $val_pasien_masuk_agustus): ?>
                                        <?php echo $val_pasien_masuk_agustus['jumlah'] ?>
                                        <?php $total_agustus=$total_agustus+$val_pasien_masuk_agustus['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_agustus; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_agustus as $val_pasien_keluarhidup_agustus): ?>
                                        <?php echo $val_pasien_keluarhidup_agustus['jumlah'] ?>
                                        <?php $pasien_keluar_agustus=$val_pasien_keluarhidup_agustus['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_agustus as $val_pasien_mati_agustus): ?>
                                        <?php echo $val_pasien_mati_agustus['jumlah'] ?>
                                        <?php $pasien_keluar_agustus=$pasien_keluar_agustus+$val_pasien_mati_agustus['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_agustus as $val_pasien_mati2_agustus): ?>
                                        <?php echo $val_pasien_mati2_agustus['jumlah'] ?>
                                        <?php $pasien_keluar_agustus=$pasien_keluar_agustus+$val_pasien_mati2_agustus['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_agustus; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==8){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_agustus-$pasien_keluar_agustus; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>9.</td>
                                    <td>September</td>
                                    <td>
                                        <?php foreach ($pasien_awal_september as $val_pasien_awal_september): ?>
                                        <?php echo $val_pasien_awal_september['jumlah'] ?>
                                        <?php $total_september=$val_pasien_awal_september['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_september as $val_pasien_masuk_september): ?>
                                        <?php echo $val_pasien_masuk_september['jumlah'] ?>
                                        <?php $total_september=$total_september+$val_pasien_masuk_september['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_september; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_september as $val_pasien_keluarhidup_september): ?>
                                        <?php echo $val_pasien_keluarhidup_september['jumlah'] ?>
                                        <?php $pasien_keluar_september=$val_pasien_keluarhidup_september['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_september as $val_pasien_mati_september): ?>
                                        <?php echo $val_pasien_mati_september['jumlah'] ?>
                                        <?php $pasien_keluar_september=$pasien_keluar_september+$val_pasien_mati_september['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_september as $val_pasien_mati2_september): ?>
                                        <?php echo $val_pasien_mati2_september['jumlah'] ?>
                                        <?php $pasien_keluar_september=$pasien_keluar_september+$val_pasien_mati2_september['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_september; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==9){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_september-$pasien_keluar_september; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>10.</td>
                                    <td>Oktober</td>
                                    <td>
                                        <?php foreach ($pasien_awal_oktober as $val_pasien_awal_oktober): ?>
                                        <?php echo $val_pasien_awal_oktober['jumlah'] ?>
                                        <?php $total_oktober=$val_pasien_awal_oktober['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_oktober as $val_pasien_masuk_oktober): ?>
                                        <?php echo $val_pasien_masuk_oktober['jumlah'] ?>
                                        <?php $total_oktober=$total_oktober+$val_pasien_masuk_oktober['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_oktober; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_oktober as $val_pasien_keluarhidup_oktober): ?>
                                        <?php echo $val_pasien_keluarhidup_oktober['jumlah'] ?>
                                        <?php $pasien_keluar_oktober=$val_pasien_keluarhidup_oktober['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_oktober as $val_pasien_mati_oktober): ?>
                                        <?php echo $val_pasien_mati_oktober['jumlah'] ?>
                                        <?php $pasien_keluar_oktober=$pasien_keluar_oktober+$val_pasien_mati_oktober['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_oktober as $val_pasien_mati2_oktober): ?>
                                        <?php echo $val_pasien_mati2_oktober['jumlah'] ?>
                                        <?php $pasien_keluar_oktober=$pasien_keluar_oktober+$val_pasien_mati2_oktober['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_oktober; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==10){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_oktober-$pasien_keluar_oktober; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>11.</td>
                                    <td>November</td>
                                    <td>
                                        <?php foreach ($pasien_awal_november as $val_pasien_awal_november): ?>
                                        <?php echo $val_pasien_awal_november['jumlah'] ?>
                                        <?php $total_november=$val_pasien_awal_november['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_november as $val_pasien_masuk_november): ?>
                                        <?php echo $val_pasien_masuk_november['jumlah'] ?>
                                        <?php $total_november=$total_november+$val_pasien_masuk_november['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_november; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_november as $val_pasien_keluarhidup_november): ?>
                                        <?php echo $val_pasien_keluarhidup_november['jumlah'] ?>
                                        <?php $pasien_keluar_november=$val_pasien_keluarhidup_november['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_november as $val_pasien_mati_november): ?>
                                        <?php echo $val_pasien_mati_november['jumlah'] ?>
                                        <?php $pasien_keluar_november=$pasien_keluar_november+$val_pasien_mati_november['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_november as $val_pasien_mati2_november): ?>
                                        <?php echo $val_pasien_mati2_november['jumlah'] ?>
                                        <?php $pasien_keluar_november=$pasien_keluar_november+$val_pasien_mati2_november['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_november; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==11){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?>
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_november-$pasien_keluar_november; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>12.</td>
                                    <td>Desember</td>
                                    <td>
                                        <?php foreach ($pasien_awal_desember as $val_pasien_awal_desember): ?>
                                        <?php echo $val_pasien_awal_desember['jumlah'] ?>
                                        <?php $total_desember=$val_pasien_awal_desember['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_masuk_desember as $val_pasien_masuk_desember): ?>
                                        <?php echo $val_pasien_masuk_desember['jumlah'] ?>
                                        <?php $total_desember=$total_desember+$val_pasien_masuk_desember['jumlah'] ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td>
                                        <?php echo $total_desember; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_keluarhidup_desember as $val_pasien_keluarhidup_desember): ?>
                                        <?php echo $val_pasien_keluarhidup_desember['jumlah'] ?>
                                        <?php $pasien_keluar_desember=$val_pasien_keluarhidup_desember['jumlah'] ?> 
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati_desember as $val_pasien_mati_desember): ?>
                                        <?php echo $val_pasien_mati_desember['jumlah'] ?>
                                        <?php $pasien_keluar_desember=$pasien_keluar_desember+$val_pasien_mati_desember['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php foreach ($pasien_mati2_desember as $val_pasien_mati2_desember): ?>
                                        <?php echo $val_pasien_mati2_desember['jumlah'] ?>
                                        <?php $pasien_keluar_desember=$pasien_keluar_desember+$val_pasien_mati2_desember['jumlah'] ?>
                                        <?php endforeach ?>                                        
                                    </td>
                                    <td>
                                        <?php echo $pasien_keluar_desember; ?>
                                    </td>
                                    <td>
                                        <?php $lama=0;?>
                                        <?php foreach ($lama_dirawat as $val_lama_dirawat): ?>
                                        <?php if($val_lama_dirawat['bulan']==12){  ?>
                                        <?php $lama = $val_lama_dirawat['jumlah'];}?> 
                                        <?php endforeach ?> 
                                        <?php echo $lama; ?> 
                                    </td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                        <?php echo $total_desember-$pasien_keluar_desember; ?>
                                    </td>
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