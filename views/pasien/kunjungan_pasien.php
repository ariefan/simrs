<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\models\Laporan;
use yii\helpers\ArrayHelper;

//$this->title = 'Kunjungan Pasien '.$pasien->mr;
?>

<div class="rekap-kunjungan-form">
    <?php $form = ActiveForm::begin(["id"=>"form-rekap"]); ?>

    <div class="row">
		<div class="col-md-12 col-sm-12">
        	<div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-red"></i>
                        <span class="caption-subject font-red bold uppercase">
                        	<?php echo "Rekap Kunjungan Pasien ke RSUD Berau " ?>     
						</span>                      
                    </div>
                </div>
                <div>          
                <span class="caption-subject font-blue bold uppercase">         
                    <?php $kode_pasien = null; ?>
                    <?php foreach ($rekap_kunjungan as $val_rekap_kunjungan): ?>
                    <?php /*if(isset($val_rekap_kunjungan))*/{ ?>
                        <br>kode pasien     : <?php echo $val_rekap_kunjungan['mr']; ?>
                        <?php $kode_pasien=$val_rekap_kunjungan['mr']; ?>
                        <br>nama     : <?php echo $val_rekap_kunjungan['nama'] ?>
                        <br>tgl. lahir     : <?php echo $val_rekap_kunjungan['tanggal_lahir'] ?>
                        <br>jenis kelamin   : <?php echo $val_rekap_kunjungan['jk'] ?>
                        <br>gol. darah : <?php echo $val_rekap_kunjungan['gol_darah'] ?>
                        <br>alamat : <?php echo $val_rekap_kunjungan['alamat'] ?>
                        <?php break;?>   
                    <?php } ?>      
                    <?php endforeach ?>
                </span>
                </div>        
                <br>
                
                <div>                
                <?php /*if(isset($kode_pasien))*/{?>
                    <div>  
                        <i class="icon-bar-chart font-green"></i>
                        <i class="icon-bar-chart font-green"></i>
                        <label class="control-label" for="cari-pasien">Cari berdasar rentang tanggal</label>
                    </div>
                    <span class="caption-subject font-green bold uppercase">  
                    <div class="col-md-4">
                    <div class="form-group">  
                        <input type="text" placeholder="Ketik tanggal mulai..." class="form-control" id="cari-tgl-awal">
                    </div>
                    </div>      
                    <div class="col-md-4">
                    <div class="form-group">
                        <input type="text" placeholder="Ketik tanggal akhir..." class="form-control" id="cari-tgl-akhir">
                    </div>
                    </div> 
                    </span>
                        <?php echo Html::a('Tampil', ['kunjungan','id'=>'000000','tgl1'=>"2017-07-06",'tgl2'=>"2017-08-17"], ['class' => 'btn btn-success']); ?>
                <?php } ?>     
                </div>
                <span class="caption-helper"></span> 
                    
                <div class="portlet-body">
                    <div class="table-scrollable table-scrollable-borderless">
                       <table class="table table-hover table-light">
                            <thead>
                                <th rowspan="2"><strong>NO.</strong></th>
                                <th rowspan="2"><strong>TANGGAL PERIKSA</strong></th>
                                <th ROWspan="2"><strong>TIPE KUNJUNGAN</strong></th>
                                <th ROWspan="2"><strong>RUANG INAP</strong></th>
                            </thead>

                            <tbody>
                                <tr><?php $no=1; ?>
                                
                                <?php foreach ($rekap_kunjungan as $val_rekap_kunjungan): ?>
                                    <?php if(isset($val_rekap_kunjungan)){ ?>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $val_rekap_kunjungan['tanggal_periksa'] ?></td>
                                    <td><?php echo $val_rekap_kunjungan['tipe_kunjungan'] ?></td>
                                    <td><?php echo $val_rekap_kunjungan['ruang_cd'] ?></td>
                                    <?php $no=$no+1; }?>                                    
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