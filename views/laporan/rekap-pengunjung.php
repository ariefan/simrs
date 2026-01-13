<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Laporan;
use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */

$this->title = 'Rekap Pengunjung Tahun '.$laporan->tahun;
?>
    <div>
        <?php echo Html::a('<span class="fa fa-stethoscope"> Unduh Rekap Pengunjung Tahun '.$laporan->tahun, Url::to(['laporan/unduh-rekap-pengunjung','thn'=>$laporan->tahun]), ['class' => 'btn btn-circle blue modalWindow']); ?>
    </div>
<br>
<div class="rekap-pengunjung-form">
    <?php $form = ActiveForm::begin(["id"=>"form-rekap"]); ?>
    <div class="row">
      <div class="col-md-6">
        <div class="portlet light bordered">
          <label>Mencari data berdasar tahun</label>
          <input type="text" placeholder="Ketik tahun..." class="form-control" id="cari-pengunjung" name="cari-pengunjung">
          <br>
            <?php 
                if(isset($_POST["cari-pengunjung"])){
                    $tahun=$_POST["cari-pengunjung"];
                }
            ?>
        </div>    
      </div>
    </div>

    <div class="form-group" id="hasil-pencarian">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption caption-md">
                            <i class="icon-bar-chart font-green"></i>
                            <span class="caption-subject font-green bold uppercase"><?php echo "Pengunjung RS berdasar jenis kelamin tahun $laporan->tahun" ?></span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <th><strong>NO.</strong></th>
                                    <th><strong>PENGUNJUNG</strong></th>
                                    <th><strong>LAKI-LAKI</strong></th>
                                    <th><strong>PEREMPUAN</strong></th>
                                    <th><strong>JUMLAH</strong></th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Rawat Jalan dan UGD</td>
                                        <?php foreach ($rajal as $val_rajal): ?>
                                        <td> <?php echo $val_rajal['pria'] ?> </td>
                                            <?php $totalLaki=$val_rajal['pria'] ?>
                                        <td> <?php echo $val_rajal['perempuan'] ?> </td>
                                            <?php $totalPerempuan=$val_rajal['perempuan'] ?>
                                        <td> <?php echo $val_rajal['jumlah'] ?> </td>
                                            <?php $totalJumlah=$val_rajal['jumlah'] ?>
                                        <?php endforeach ?>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Rawat Inap</td>
                                        <?php foreach ($ranap as $val_ranap): ?>
                                        <td> <?php echo $val_ranap['pria'] ?> </td>
                                            <?php $totalLaki=$totalLaki+$val_ranap['pria'] ?>
                                        <td> <?php echo $val_ranap['perempuan'] ?> </td>
                                            <?php $totalPerempuan=$totalPerempuan+$val_ranap['perempuan'] ?>
                                        <td> <?php echo $val_ranap['jumlah'] ?> </td>
                                            <?php $totalJumlah=$totalJumlah+$val_ranap['jumlah'] ?>
                                        <?php endforeach ?>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><H4>JUMLAH</H4></td>
                                        <td>
                                            <?php echo $totalLaki ?> 
                                        </td>
                                        <td>
                                            <?php echo $totalPerempuan ?> 
                                        </td>
                                        <td>
                                            <?php echo $totalJumlah ?> 
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
                            <span class="caption-subject font-green bold uppercase"><?php echo "Pengunjung RS dirinci berdasarkan penjamin tahun $laporan->tahun" ?></span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <th><strong>NO.</strong></th>
                                    <th><strong>PENGUNJUNG</strong></th>
                                    <th><strong>DENGAN PENJAMIN</strong></th>
                                    <th><strong>TANPA PENJAMIN</strong></th>
                                    <th><strong>JUMLAH</strong></th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Rawat Jalan dan UGD</td>
                                        <?php foreach ($jalan_penjamin as $val_jalan_penjamin): ?>
                                        <td> <?php echo $val_jalan_penjamin['penjamin'] ?> </td>
                                            <?php $totalpenjamin=$val_jalan_penjamin['penjamin'] ?>
                                        <td> <?php echo $val_jalan_penjamin['nonpenjamin'] ?> </td>
                                            <?php $totalnonpenjamin=$val_jalan_penjamin['nonpenjamin'] ?>
                                        <td> <?php echo $val_jalan_penjamin['jumlah'] ?> </td>
                                            <?php $totalJumlah=$val_jalan_penjamin['jumlah'] ?>
                                        <?php endforeach ?>
                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Rawat Inap</td>
                                        <?php foreach ($inap_penjamin as $val_inap_penjamin): ?>
                                        <td> <?php echo $val_inap_penjamin['penjamin'] ?> </td>
                                            <?php $totalpenjamin=$totalpenjamin+$val_inap_penjamin['penjamin'] ?>
                                        <td> <?php echo $val_inap_penjamin['nonpenjamin'] ?> </td>
                                            <?php $totalnonpenjamin=$totalnonpenjamin+$val_inap_penjamin['nonpenjamin'] ?>
                                        <td> <?php echo $val_inap_penjamin['jumlah'] ?> </td>
                                            <?php $totalJumlah=$totalJumlah+$val_inap_penjamin['jumlah'] ?>
                                        <?php endforeach ?>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><H4>JUMLAH</H4></td>
                                        <td>
                                            <?php echo $totalpenjamin ?> 
                                        </td>
                                        <td>
                                            <?php echo $totalnonpenjamin ?> 
                                        </td>
                                        <td>
                                            <?php echo $totalJumlah ?> 
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
                            <span class="caption-subject font-green bold uppercase"><?php echo "Frekuensi Kunjungan Pasien ke RSUD dirinci Berdasarkan jenis
        Penjamin tahun $laporan->tahun" ?></span>
                            <span class="caption-helper"></span>
                        </div>
                        <div class="actions">
                            
                        </div>
                    </div>

                    <div class="portlet-body">
                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th rowspan="2"><strong>NO.</strong></th>
                                        <th rowspan="2"><strong>FREK.</strong></th>
                                        <th colspan="3"><strong>KUNJUNGAN DENGAN PENJAMIN</strong></th>
                                        <th colspan="3"><strong>KUNJUNGAN TANPA PENJAMIN</strong></th>
                                        <th colspan="3"><strong>TOTAL KUNJUNGAN</strong></th>
                                    </tr>
                                    <th><strong>RAJAL</strong></th>
                                    <th><strong>IRNA</strong></th>
                                    <th><strong>JUMLAH</strong></th>
                                    <th><strong>RAJAL</strong></th>
                                    <th><strong>IRNA</strong></th>
                                    <th><strong>JUMLAH</strong></th>
                                    <th><strong>RAJAL</strong></th>
                                    <th><strong>IRNA</strong></th>
                                    <th><strong>JUMLAH</strong></th>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td><strong>1.</strong></td>
                                        <td><strong>1-KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek1 as $val_rajalpenjaminfrek1): ?>
                                                <?php echo $val_rajalpenjaminfrek1['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek1=$val_rajalpenjaminfrek1['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek1 as $val_inappenjaminfrek1): ?>
                                                <?php echo $val_inappenjaminfrek1['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek1=$val_inappenjaminfrek1['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1 + $total_val_inappenjaminfrek1 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek1 as $val_rajalnonpenjaminfrek1): ?>
                                                <?php echo $val_rajalnonpenjaminfrek1['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek1=$val_rajalnonpenjaminfrek1['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek1 as $val_inapnonpenjaminfrek1): ?>
                                                <?php echo $val_inapnonpenjaminfrek1['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek1=$val_inapnonpenjaminfrek1['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek1 + $total_val_inapnonpenjaminfrek1 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1 + $total_val_rajalnonpenjaminfrek1 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek1 + $total_val_inapnonpenjaminfrek1 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1 + $total_val_rajalnonpenjaminfrek1 + $total_val_inappenjaminfrek1 + $total_val_inapnonpenjaminfrek1 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>2.</strong></td>
                                        <td><strong>2-KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek2 as $val_rajalpenjaminfrek2): ?>
                                                <?php echo $val_rajalpenjaminfrek2['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek2=$val_rajalpenjaminfrek2['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek2 as $val_inappenjaminfrek2): ?>
                                                <?php echo $val_inappenjaminfrek2['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek2=$val_inappenjaminfrek2['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek2 + $total_val_inappenjaminfrek2 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek2 as $val_rajalnonpenjaminfrek2): ?>
                                                <?php echo $val_rajalnonpenjaminfrek2['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek2=$val_rajalnonpenjaminfrek2['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek2 as $val_inapnonpenjaminfrek2): ?>
                                                <?php echo $val_inapnonpenjaminfrek2['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek2=$val_inapnonpenjaminfrek2['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek2 + $total_val_inapnonpenjaminfrek2 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek2 + $total_val_rajalnonpenjaminfrek2 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek2 + $total_val_inapnonpenjaminfrek2 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek2 + $total_val_rajalnonpenjaminfrek2 + $total_val_inappenjaminfrek2 + $total_val_inapnonpenjaminfrek2 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>3.</strong></td>
                                        <td><strong>3-KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek3 as $val_rajalpenjaminfrek3): ?>
                                                <?php echo $val_rajalpenjaminfrek3['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek3=$val_rajalpenjaminfrek3['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek3 as $val_inappenjaminfrek3): ?>
                                                <?php echo $val_inappenjaminfrek3['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek3=$val_inappenjaminfrek3['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek3 + $total_val_inappenjaminfrek3 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek3 as $val_rajalnonpenjaminfrek3): ?>
                                                <?php echo $val_rajalnonpenjaminfrek3['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek3=$val_rajalnonpenjaminfrek3['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek3 as $val_inapnonpenjaminfrek3): ?>
                                                <?php echo $val_inapnonpenjaminfrek3['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek3=$val_inapnonpenjaminfrek3['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek3 + $total_val_inapnonpenjaminfrek3 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek3 + $total_val_rajalnonpenjaminfrek3 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek3 + $total_val_inapnonpenjaminfrek3 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek3 + $total_val_rajalnonpenjaminfrek3 + $total_val_inappenjaminfrek3 + $total_val_inapnonpenjaminfrek3 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>4.</strong></td>
                                        <td><strong>4-KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek4 as $val_rajalpenjaminfrek4): ?>
                                                <?php echo $val_rajalpenjaminfrek4['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek4=$val_rajalpenjaminfrek4['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek4 as $val_inappenjaminfrek4): ?>
                                                <?php echo $val_inappenjaminfrek4['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek4=$val_inappenjaminfrek4['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek4 + $total_val_inappenjaminfrek4 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek4 as $val_rajalnonpenjaminfrek4): ?>
                                                <?php echo $val_rajalnonpenjaminfrek4['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek4=$val_rajalnonpenjaminfrek4['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek4 as $val_inapnonpenjaminfrek4): ?>
                                                <?php echo $val_inapnonpenjaminfrek4['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek4=$val_inapnonpenjaminfrek4['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek4 + $total_val_inapnonpenjaminfrek4 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek4 + $total_val_rajalnonpenjaminfrek4 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek4 + $total_val_inapnonpenjaminfrek4 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek4 + $total_val_rajalnonpenjaminfrek4 + $total_val_inappenjaminfrek4 + $total_val_inapnonpenjaminfrek4 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>5.</strong></td>
                                        <td><strong>5-KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek5 as $val_rajalpenjaminfrek5): ?>
                                                <?php echo $val_rajalpenjaminfrek5['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek5=$val_rajalpenjaminfrek5['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek5 as $val_inappenjaminfrek5): ?>
                                                <?php echo $val_inappenjaminfrek5['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek5=$val_inappenjaminfrek5['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek5 + $total_val_inappenjaminfrek5 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek5 as $val_rajalnonpenjaminfrek5): ?>
                                                <?php echo $val_rajalnonpenjaminfrek5['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek5=$val_rajalnonpenjaminfrek5['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek5 as $val_inapnonpenjaminfrek5): ?>
                                                <?php echo $val_inapnonpenjaminfrek5['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek5=$val_inapnonpenjaminfrek5['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek5 + $total_val_inapnonpenjaminfrek5 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek5 + $total_val_rajalnonpenjaminfrek5 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek5 + $total_val_inapnonpenjaminfrek5 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek5 + $total_val_rajalnonpenjaminfrek5 + $total_val_inappenjaminfrek5 + $total_val_inapnonpenjaminfrek5 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>6.</strong></td>
                                        <td><strong>6-10 KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek6 as $val_rajalpenjaminfrek6): ?>
                                                <?php echo $val_rajalpenjaminfrek6['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek6=$val_rajalpenjaminfrek6['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek6 as $val_inappenjaminfrek6): ?>
                                                <?php echo $val_inappenjaminfrek6['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek6=$val_inappenjaminfrek6['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek6 + $total_val_inappenjaminfrek6 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek6 as $val_rajalnonpenjaminfrek6): ?>
                                                <?php echo $val_rajalnonpenjaminfrek6['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek6=$val_rajalnonpenjaminfrek6['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek6 as $val_inapnonpenjaminfrek6): ?>
                                                <?php echo $val_inapnonpenjaminfrek6['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek6=$val_inapnonpenjaminfrek6['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek6 + $total_val_inapnonpenjaminfrek6 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek6 + $total_val_rajalnonpenjaminfrek6 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek6 + $total_val_inapnonpenjaminfrek6 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek6 + $total_val_rajalnonpenjaminfrek6 + $total_val_inappenjaminfrek6 + $total_val_inapnonpenjaminfrek6 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>7.</strong></td>
                                        <td><strong>Lebih dari 10 KALI</strong></td>
                                        <td>
                                            <?php foreach ($rajalpenjaminfrek10 as $val_rajalpenjaminfrek10): ?>
                                                <?php echo $val_rajalpenjaminfrek10['jumlah'] ?>
                                                <?php $total_val_rajalpenjaminfrek10=$val_rajalpenjaminfrek10['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inappenjaminfrek10 as $val_inappenjaminfrek10): ?>
                                                <?php echo $val_inappenjaminfrek10['jumlah'] ?>
                                                <?php $total_val_inappenjaminfrek10=$val_inappenjaminfrek10['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek10 + $total_val_inappenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php foreach ($rajalnonpenjaminfrek10 as $val_rajalnonpenjaminfrek10): ?>
                                                <?php echo $val_rajalnonpenjaminfrek10['jumlah'] ?>
                                                <?php $total_val_rajalnonpenjaminfrek10=$val_rajalnonpenjaminfrek10['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php foreach ($inapnonpenjaminfrek10 as $val_inapnonpenjaminfrek10): ?>
                                                <?php echo $val_inapnonpenjaminfrek10['jumlah'] ?>
                                                <?php $total_val_inapnonpenjaminfrek10=$val_inapnonpenjaminfrek10['jumlah'] ?>
                                            <?php endforeach ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek10 + $total_val_inapnonpenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek10 + $total_val_rajalnonpenjaminfrek10 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek10 + $total_val_inapnonpenjaminfrek10 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek10 + $total_val_rajalnonpenjaminfrek10 + $total_val_inappenjaminfrek10 + $total_val_inapnonpenjaminfrek10 ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><strong><H4>JUMLAH</H4></strong></td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1+$total_val_rajalpenjaminfrek2+$total_val_rajalpenjaminfrek3+$total_val_rajalpenjaminfrek4+$total_val_rajalpenjaminfrek5+$total_val_rajalpenjaminfrek6+$total_val_rajalpenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek1+$total_val_inappenjaminfrek2+$total_val_inappenjaminfrek3+$total_val_inappenjaminfrek4+$total_val_inappenjaminfrek5+$total_val_inappenjaminfrek6+$total_val_inappenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1+$total_val_inappenjaminfrek1+$total_val_rajalpenjaminfrek2+$total_val_inappenjaminfrek2+$total_val_rajalpenjaminfrek3+$total_val_inappenjaminfrek3+$total_val_rajalpenjaminfrek4+$total_val_inappenjaminfrek4+$total_val_rajalpenjaminfrek5+$total_val_inappenjaminfrek5+$total_val_rajalpenjaminfrek6+$total_val_inappenjaminfrek6+$total_val_rajalpenjaminfrek10+$total_val_inappenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek1+$total_val_rajalnonpenjaminfrek2+$total_val_rajalnonpenjaminfrek3+$total_val_rajalnonpenjaminfrek4+$total_val_rajalnonpenjaminfrek5+$total_val_rajalnonpenjaminfrek6+$total_val_rajalnonpenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_inapnonpenjaminfrek1+$total_val_inapnonpenjaminfrek2+$total_val_inapnonpenjaminfrek3+$total_val_inapnonpenjaminfrek4+$total_val_inapnonpenjaminfrek5+$total_val_inapnonpenjaminfrek6+$total_val_inapnonpenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalnonpenjaminfrek1+$total_val_inapnonpenjaminfrek1+$total_val_rajalnonpenjaminfrek2+$total_val_inapnonpenjaminfrek2+$total_val_rajalnonpenjaminfrek3+$total_val_inapnonpenjaminfrek3+$total_val_rajalnonpenjaminfrek4+$total_val_inapnonpenjaminfrek4+$total_val_rajalnonpenjaminfrek5+$total_val_inapnonpenjaminfrek5+$total_val_rajalnonpenjaminfrek6+$total_val_inapnonpenjaminfrek6+$total_val_rajalnonpenjaminfrek10+$total_val_inapnonpenjaminfrek10 ?>
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1+$total_val_rajalnonpenjaminfrek1+$total_val_rajalpenjaminfrek2+$total_val_rajalnonpenjaminfrek2+$total_val_rajalpenjaminfrek3+$total_val_rajalnonpenjaminfrek3+$total_val_rajalpenjaminfrek4+$total_val_rajalnonpenjaminfrek4+$total_val_rajalpenjaminfrek5+$total_val_rajalnonpenjaminfrek5+$total_val_rajalpenjaminfrek6+$total_val_rajalnonpenjaminfrek6+$total_val_rajalpenjaminfrek10+$total_val_rajalnonpenjaminfrek10 ?>     
                                        </td>
                                        <td>
                                            <?php echo $total_val_inappenjaminfrek1+$total_val_inapnonpenjaminfrek1+$total_val_inappenjaminfrek2+$total_val_inapnonpenjaminfrek2+$total_val_inappenjaminfrek3+$total_val_inapnonpenjaminfrek3+$total_val_inappenjaminfrek4+$total_val_inapnonpenjaminfrek4+$total_val_inappenjaminfrek5+$total_val_inapnonpenjaminfrek5+$total_val_inappenjaminfrek6+$total_val_inapnonpenjaminfrek6+$total_val_inappenjaminfrek10+$total_val_inapnonpenjaminfrek10 ?> 
                                        </td>
                                        <td>
                                            <?php echo $total_val_rajalpenjaminfrek1+$total_val_rajalnonpenjaminfrek1+$total_val_inappenjaminfrek1+$total_val_inapnonpenjaminfrek1+$total_val_rajalpenjaminfrek2+$total_val_rajalnonpenjaminfrek2+$total_val_inappenjaminfrek2+$total_val_inapnonpenjaminfrek2+$total_val_rajalpenjaminfrek3+$total_val_rajalnonpenjaminfrek3+$total_val_inappenjaminfrek3+$total_val_inapnonpenjaminfrek3+$total_val_rajalpenjaminfrek4+$total_val_rajalnonpenjaminfrek4+$total_val_inappenjaminfrek4+$total_val_inapnonpenjaminfrek4+$total_val_rajalpenjaminfrek5+$total_val_rajalnonpenjaminfrek5+$total_val_inappenjaminfrek5+$total_val_inapnonpenjaminfrek5+$total_val_rajalpenjaminfrek6+$total_val_rajalnonpenjaminfrek6+$total_val_inappenjaminfrek6+$total_val_inapnonpenjaminfrek6+$total_val_rajalpenjaminfrek10+$total_val_rajalnonpenjaminfrek10+$total_val_inappenjaminfrek10+$total_val_inapnonpenjaminfrek10 ?> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>




    </div>
    <?php ActiveForm::end(); ?>    
</div>
