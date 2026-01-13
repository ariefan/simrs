<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use app\models\Kunjungan;
use app\models\RekamMedis;
use app\models\Dokter;

/* @var $this yii\web\View */
/* @var $model app\models\RekamMedis */

$this->title = 'Data Rekam Medis';
$this->params['breadcrumbs'][] = ['label' => 'Rekam Medis', 'url' => ['index']];
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
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            
            <div class="portlet-body">
                <div>
                    <?php $histori_rm = Kunjungan::find()->rightJoin('rekam_medis','rekam_medis.kunjungan_id=kunjungan.kunjungan_id')->where(['rekam_medis.mr'=>$model->kunjungan->mr0->mr])->all();
                    ?>

                    <p>Histori Pemeriksaan : </p>
                    <ul class="pagination">
                        <?php 
                        if(!empty($histori_rm))
                        foreach($histori_rm as $val): ?>
                            <?php 
                            $time = strtotime($val['tanggal_periksa']);
                            $myFormatForView = date("d F Y", $time);
                            if(isset($val->rekamMedis[0]->rm_id) && $val->rekamMedis[0]->rm_id==$model->rm_id)
                            ?>
                            <li <?= $val->rekamMedis[0]->rm_id==$model->rm_id ? 'class="active"' : "" ?>>
                                <a href="<?= Url::to(['rekam-medis/view','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val->rekamMedis[0]->rm_id, Yii::$app->params['kunciInggris'] ))]) ?>"> <?= $myFormatForView ?> </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if ($model->kunjungan->tipe_kunjungan=='Rawat Inap'): 
                    $histori_rm_inap = RekamMedis::find()->where(['kunjungan_id'=>$model->kunjungan->kunjungan_id])->orderBy(['tgl_periksa'=>SORT_ASC])->all();
                    ?>
                    <p>Histori Rawat Inap : </p>
                    <ul class="pagination">
                        <?php 
                        $alreadyDiagToday = 0;
                        if(!empty($histori_rm_inap))
                        foreach($histori_rm_inap as $val): ?>
                            <?php 
                            $time = strtotime($val['tgl_periksa']);
                            $myFormatForView = date("d F Y", $time);
                            if ($myFormatForView == date("d F Y"))
                                $alreadyDiagToday = 1;
                            if($val['rm_id']==$model->rm_id)
                            ?>
                            <li <?= $val['rm_id']==$model->rm_id ? 'class="active"' : "" ?>>
                                <a href="<?= Url::to(['rekam-medis/view','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['rm_id'], Yii::$app->params['kunciInggris'] ))]) ?>"> <?= $myFormatForView ?> </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endIf; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PROFILE SIDEBAR -->
        <div class="profile-sidebar">
            <!-- PORTLET MAIN -->
            <div class="portlet light profile-sidebar-portlet bordered">
                <?php if(!empty($pasien->foto)): ?>
                <div class="profile-userpic">
                    <?= Html::img('@web/'.$pasien->foto,['class'=>'img-responsive']) ?>
                </div>
                <?php endif; ?>
                <!-- SIDEBAR USER TITLE -->
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name"> <?= $pasien->nama ?> </div>
                    <div class="profile-usertitle-job"> <?= !(empty($pasien->tanggal_lahir)) ? $model->getAge($pasien->tanggal_lahir) : 0 ?> Tahun </div>
                    <div class="profile-usertitle-job"> <?= $pasien->alamat ?> </div>
                </div>
                <!-- END SIDEBAR USER TITLE -->
                <!-- SIDEBAR BUTTONS -->
                <div class="profile-userbuttons">
                    <?= Html::a('Edit', Url::to(['rekam-medis/update','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ))]),['class'=>'btn btn-circle green btn-sm']) ?>
                    <?= Html::button('Detail Pasien', ['value'=>Url::to(['pasien/view-ajax','id'=>$pasien->mr]),'class' => 'btn btn-circle green btn-sm modalWindow']) ?>
                    <?= Html::a('Unduh', Url::to(['rekam-medis/unduh-rm','id'=>utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ))]),['class' => 'btn btn-circle red btn-sm']) ?>
                </div>
                <!-- END SIDEBAR BUTTONS -->
                <!-- SIDEBAR MENU -->
                <div class="profile-usermenu">
                    
                </div>
                <!-- END MENU -->
            </div>
            <!-- END PORTLET MAIN -->
            <!-- PORTLET MAIN -->
            <div class="portlet light bordered">
                <!-- STAT -->
                <div class="row list-separated profile-stat">
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= $model->tekanan_darah ?> </div>
                        <div class="uppercase profile-stat-text"> TD (120/80) </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= $model->nadi ?> </div>
                        <div class="uppercase profile-stat-text"> Nadi (x/min) </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= $model->respirasi_rate ?> </div>
                        <div class="uppercase profile-stat-text"> Resp Rate (x/min) </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= $model->suhu ?> </div>
                        <div class="uppercase profile-stat-text"> Suhu (C) </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= $model->berat_badan ?> </div>
                        <div class="uppercase profile-stat-text"> BB (kg) </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= $model->tinggi_badan ?> </div>
                        <div class="uppercase profile-stat-text"> TB (cm) </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6"></div>
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="uppercase profile-stat-title"> <?= number_format($model->bmi,2,',','') ?> </div>
                        <div class="uppercase profile-stat-text"> BMI </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-6"></div>
                    
                </div>
                <!-- END STAT -->
                <div>
                    <h4 class="profile-desc-title">Keluhan Utama</h4>
                    <span class="profile-desc-text"> <?= $model->keluhan_utama ?> </span>
                    <div class="margin-top-20 profile-desc-link">
                    </div>
                    
                </div>
                <div>
                    <h4 class="profile-desc-title">Tanggal Periksa</h4>
                    <span class="profile-desc-text"> <?= $model->created ?> </span>
                    <div class="margin-top-20 profile-desc-link">
                    </div>
                    
                </div>
                <div>
                    <h4 class="profile-desc-title">Dokter</h4>
                    <span class="profile-desc-text"> <?= $model->dpjp->nama ?> </span>
                    <div class="margin-top-20 profile-desc-link">
                    </div>
                    
                </div>
            </div>

            <!-- END PORTLET MAIN -->
        </div>
        <div class="profile-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        
                        <div class="portlet-body">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width:20%">(S) Subyektif</th>
                                        <td><?= $model->anamnesis ?></td>
                                    </tr>
                                    <tr>
                                        <th>(O) Obyektif</th>
                                        <td><?= $model->pemeriksaan_fisik ?></td>
                                    </tr>
                                    <tr>
                                        <th>(A) Assesment</th>
                                        <td><?= $model->assesment ?></td>
                                    </tr>
                                    <tr>
                                        <th>(P) Plan</th>
                                        <td><?= $model->plan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Catatan Keluar</th>
                                        <td><?= $model->kunjungan->catatan_keluar ?></td>
                                    </tr>
                                    <tr>
                                        <th>Hasil Penunjang</th>
                                        <td><?= $model->hasil_penunjang ?></td>
                                    </tr>
                                    <tr>
                                        <th>Saran Pemeriksaan</th>
                                        <td><?= $model->saran_pemeriksaan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Diagnosis 1 (ICD-10)</th>
                                        <td>
                                            <ul>
                                            <?php foreach($rm_diagnosis as $value): ?>
                                                <li><?= !empty($value['kode']) ? $value['kode']." - ".$value['nama_diagnosis'] : $value['nama_diagnosis'] ?></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Diagnosis 2 (ICD-10)</th>
                                        <td>
                                            <ul>
                                            <?php foreach($rm_diagnosis_banding as $value): ?>
                                                <li><?= !empty($value['kode']) ? $value['kode']." - ".$value['nama_diagnosis'] : $value['nama_diagnosis'] ?></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tindakan</th>
                                        <td>
                                            <ul>
                                            <?php foreach($rm_tindakan as $value): ?>
                                                <li><?= $value->jumlah ?>X <?= $value['nama_tindakan'] ?> (<?= @$value->dokter->dokter->nama ?>)</li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Deskripsi Tindakan</th>
                                        <td><?= $model->deskripsi_tindakan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alergi Obat</th>
                                        <td><?= $model->alergi_obat ?></td>
                                    </tr>
                                    <tr>
                                        <th>Obat</th>
                                        <td>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Obat</th>
                                                        <th>Jumlah</th>
                                                        <th>Satuan</th>
                                                        <th>Signa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tbody>
                                                        <?php foreach($rm_obat as $value): ?>
                                                            <tr>
                                                                <td><?= $value['nama_obat'] ?></td>
                                                                <td><?= $value['jumlah'] ?></td>
                                                                <td><?= $value['satuan'] ?></td>
                                                                <td><?= $value['signa'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Obat Racik</th>
                                        <td>
                                            <?php foreach($rm_obatracik as $value): ?>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Obat</th>
                                                        <th>Jumlah</th>
                                                        <th>Satuan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tbody>
                                                        <?php foreach($rm_obatracik_komponen[$value['racik_id']] as $val): ?>
                                                            <tr>
                                                                <td><?= $val['nama_obat'] ?></td>
                                                                <td><?= $val['jumlah'] ?></td>
                                                                <td><?= $val['satuan'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td colspan="2">M.F.Pulv : <?= $value['jumlah'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">Signa : <?= $value['signa'] ?></td>
                                                        </tr>
                                                    </tbody>
                                                </tbody>
                                            </table>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Laboratorium</th>
                                        <td>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th>Catatan</th>
                                                        <th colspan="2">Hasil</th>
                                                        <th>Dokter</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($rm_lab as $value): ?>
                                                    <tr>
                                                        <td><?= $value['nama'] ?></td>
                                                        <td><?= $value['catatan'] ?></td>
                                                        <td><?= $value['hasil'] ?></td>
                                                        <td><td>
                                                            <?php if(!empty($value['hasil_file'])): ?>
                                                            <?= Html::img('@web/rm_penunjang/'.$value['hasil_file'],['width'=>'300px']) ?>
                                                            <?php endif; ?>
                                                        </td></td>
                                                        <td><?= Dokter::findOne($value['dokter'])->nama ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Radiologi</th>
                                        <td>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th>Catatan</th>
                                                        <th colspan="2">Hasil</th>
                                                        <th>Dokter</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($rm_rad as $value): ?>
                                                    <tr>
                                                        <td><?= $value['nama'] ?></td>
                                                        <td><?= $value['catatan'] ?></td>
                                                        <td><?= $value['hasil'] ?></td>
                                                        <td>
                                                            <?php if(!empty($value['hasil_file'])): ?>
                                                            <?= Html::img('@web/rm_penunjang/'.$value['hasil_file'],['width'=>'300px']) ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= Dokter::findOne($value['dokter'])->nama ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END PORTLET -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3>Upload File Penunjang</h3>
                    <?php 
                    $id = utf8_encode(Yii::$app->security->encryptByKey( $model->rm_id, Yii::$app->params['kunciInggris'] ));
                    echo \kato\DropZone::widget([
                           'options' => [
                               'url' => Url::to(['rekam-medis/upload','id'=>$id]),
                               'maxFilesize' => '2',
                           ],
                           'clientEvents' => [
                               'complete' => "function(file){console.log(file)}",
                               'removedfile' => "function(file){alert(file.name + ' is removed')}"
                           ],
                       ]);
                    ?>
                    <h4>File Penunjang Saat Ini</h4>
                    <?php foreach($data_penunjang as $key => $val): ?>
                            
                            <?php 
                            try {
                                getimagesize(Url::to('@web/'.$val['path'],true)) > 0 ? Html::a(Html::img('@web/'.$val['path'],['style'=>'height:170px','class'=>'img-responsive']),Url::to('@web/'.$val['path'],true)) : Html::a(substr(str_replace('rm_penunjang/', '', $val['path']),5),Url::to('@web/'.$val['path'],true),['class' => 'btn btn-lg green']);

                                echo Html::a('<i class="fa fa-trash-o"></i>', Url::to(['rekam-medis/delete-penunjang','id'=>utf8_encode(Yii::$app->security->encryptByKey( $val['id'], Yii::$app->params['kunciInggris'] ))]), [
                                    'title' => Yii::t('yii', 'Hapus'),
                                    'class'=> 'btn dark btn-sm btn-outline sbold uppercase',
                                    'data-confirm' => Yii::t('yii', 'Apakah Anda Yakin akan menghapus File ini?'),
                                    'data-method' => 'post',
                                ]);  
                            } catch(\Exception $e) {

                            }
                            

                            ?>
                        
                        
                    <?php endforeach; ?>
                </div>
            </div>
            
        </div>
        
    </div>
</div>

<?php
//$this->registerJsFile('@web/metronic/pages/scripts/profile.min.js',['depends'=>'app\assets\MetronicAsset']);
$script = <<< JS
    $(function(){
        $('.modalWindow').click(function(){
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value'))
        })
    });

JS;

$this->registerJs($script);
?>

<?php
$readOnly = true;
if ($model->kunjungan->tipe_kunjungan=='Rawat Inap' && !$model->isNewRecord)
    echo $this->render('_formRawatInap', compact('model','readOnly')) ?>
