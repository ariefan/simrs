<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use app\models\Pasien;
use app\models\Obat;
use app\models\RefCaraPulang;
use app\models\User;
use app\models\Tindakan;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use app\models\bridging\Eklaim;
$poliHaveRl = ['POLIIGD','POLIGIGI','POLIBIDAN'];
$pasien = new Pasien();
$start_counter = isset($rm_obatracik) ? count($rm_obatracik) + 1 : 1;
/* @var $this yii\web\View */
/* @var $model app\models\RekamMedis */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
    Modal::begin([
            'id' => 'modal',
        ]);

    echo "<div id='modalContent'></div>";

    Modal::end();

?>
<?php $form = ActiveForm::begin(['id'=>'form-rm']); ?>
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


<?php
    Modal::begin([
            'id' => 'checkout',
            'header' => '<h2>Status Keluar</h2>',
        ]);

            echo Html::dropDownList('status_keluar','',[
                    'PULANG'=>'Pulang',
                    'RUJUKRANAP'=>'Rujuk Rawat Inap',
                ],['class'=>'form-control status_keluar','prompt'=>'Pilihan Keluar']);

            echo "<div id='checkoutContent' style='display:none'>".
                $form->field($kunjungan, 'jenis_keluar')->dropDownList(/*[
                    'Hidup'=>'Hidup',
                    'Mati < 48 Jam'=>'Mati < 48 Jam',
                    'Mati > 48 Jam'=>'Mati > 48 Jam'
                ]*/
                ArrayHelper::map(RefCaraPulang::find()->all(),'pulang_id','pulang_nama')
                );

            echo $form->field($kunjungan, 'catatan_keluar')->textArea();


            echo ($kunjungan->tipe_kunjungan=='Rawat Jalan')?Html::submitButton('Simpan', ['id'=>'selesai_submit','class' => 'btn red checkout','name'=>'Selesai','value'=>'selesai']):Html::submitButton('Simpan', ['id'=>'selesai_submit','class' => 'btn red checkout','name'=>'Selesai','value'=>'selesai']);

            echo "</div>";

    Modal::end();

?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">Data Pasien</span>
                </div>
            </div>
            <div class="portlet-body form">
                <?= $kunjungan->mr0->mr ?>
                <br/>

                <strong><?= $kunjungan->mr0->nama ?></strong>
                <br/>

                <?= $kunjungan->mr0->alamat ?>
                <br/>

                <?= $kunjungan->mr0->jk ?>
                <br/>

                <?= $model->getAge($kunjungan->mr0->tanggal_lahir) ?> Tahun
                
                <div>
                <?= $this->render('rmPagination', compact('eklaim','modelRmInap','modelRmInapGizi','model','histori_rm','rm_diagnosis_id','rm_diagnosis_text','rm_diagnosis_banding_id','rm_diagnosis_banding_text','rm_tindakan','rm_obat','rm_obatracik','rm_obatracik_komponen','kunjungan','data_exist','data_rad','data_lab')) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-settings font-red-sunglo"></i>
                    <span class="caption-subject bold uppercase">REKAM MEDIS</span>
                </div>
            </div>
            
            <br/>
            <div class="portlet-body form">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#tab_1" data-toggle="tab"> PENGECEKAN </a>
                    </li>
                    <li>
                        <a href="#tab_2" data-toggle="tab"> PEMERIKSAAN </a>
                    </li>
                    <li>
                        <a href="#tab_5" data-toggle="tab"> PEMERIKSAAN LANJUT </a>
                    </li>
                    <li>
                        <a href="#tab_9" data-toggle="tab"> TINDAKAN </a>
                    </li>
                    <?php if ($kunjungan->tipe_kunjungan=='Rawat Inap'){ ?>
                        <li>
                            <a href="#tab_12" data-toggle="tab"> GIZI </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="#tab_3" data-toggle="tab"> OBAT </a>
                    </li>
                    <li>
                        <a href="#tab_4" data-toggle="tab"> OBAT RACIK </a>
                    </li>
                    <li>
                        <a href="#tab_6" data-toggle="tab"> LABORATORIUM </a>
                    </li>
                    <li>
                        <a href="#tab_7" data-toggle="tab"> RADIOLOGI </a>
                    </li>
                    <?php if ($kunjungan->eklaim!=false): ?>
                        <li>
                            <a href="#tab_8" data-toggle="tab"> EKLAIM </a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array($kunjungan->medunit_cd, $poliHaveRl)): ?>
                        <li>
                            <a href="#tab_10" data-toggle="tab"> LAPORAN RL </a>
                        </li>
                    <?php endif; ?>
                    <?php if ($kunjungan->tipe_kunjungan=='Rawat Inap' && floor((time()-strtotime($kunjungan->mr0->tanggal_lahir)) / (60 * 60 * 24))<=6): ?>
                        <li>
                            <a href="#tab_11" data-toggle="tab"> PERINATOLOGI </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active in" id="tab_1">
                        <label><input type='checkbox' id='useDefault' value="1">Nilai Normal</label>
                        <div class="row">
                            <div class="col-md-4"><?= $form->field($model, 'tekanan_darah')->textInput(['class'=>'tekanan_darah form-control','maxlength' => true]) ?></div>
                            <div class="col-md-4"><?= $form->field($model, 'nadi')->textInput(['class'=>'nadi  form-control']) ?></div>
                            <div class="col-md-4"><?= $form->field($model, 'respirasi_rate')->textInput(['class'=>'respirasi_rate form-control']) ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><?= $form->field($model, 'suhu')->textInput(['class'=>'suhu form-control']) ?></div>
                            <div class="col-md-4"><?= $form->field($model, 'berat_badan')->textInput() ?></div>
                            <div class="col-md-4"><?= $form->field($model, 'tinggi_badan')->textInput() ?></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="rekammedis-bmi">BMI</label>
                            <label class="control-label" id="bmi_hasil"></label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Keluhan Utama</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'keluhan_utama',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>
                        
                    </div>
                    <div class="tab-pane fade" id="tab_2">
                        <div class="form-group">
                            <label class="control-label">Anamnesis (Subyekif)</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'anamnesis',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Pemeriksaan Fisik (Obyektif)</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'pemeriksaan_fisik',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label">Assesment</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'assesment',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Plan</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'plan',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>

                        
                    </div>
                    <div class="more_adv tab-pane fade" id="tab_5">
                        <div class="form-group">
                            <?php

                            echo '<label class="control-label">Diagnosis (ICD-10)</label>';
                            echo Select2::widget([
                                'name' => 'diagnosis',
                                'options' => ['placeholder' => 'Pilih Diagnosis', 'multiple' => true],
                                'initValueText' => isset($rm_diagnosis_text) ? $rm_diagnosis_text : [],
                                'value' => isset($rm_diagnosis_id) ? $rm_diagnosis_id : [],
                                'pluginOptions' => [
                                    'tags' => false,
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['diagnosis/cari']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(dat) { return dat.text; }'),
                                    'templateSelection' => new JsExpression('function (dat) { return dat.text; }'),
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="form-group">
                            <?php
                            echo '<label class="control-label">Diagnosis 2 (ICD-10)</label>';
                            echo Select2::widget([
                                'name' => 'diagnosis_banding',
                                'options' => ['placeholder' => 'Pilih Diagnosis Banding', 'multiple' => true],
                                'initValueText' => isset($rm_diagnosis_banding_text) ? $rm_diagnosis_banding_text : [],
                                'value' => isset($rm_diagnosis_banding_id) ? $rm_diagnosis_banding_id : [],
                                'pluginOptions' => [
                                    'tags' => false,
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['diagnosis/cari']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(dat) { return dat.text; }'),
                                    'templateSelection' => new JsExpression('function (dat) { return dat.text; }'),
                                ],
                            ]);
                            ?>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Hasil Penunjang</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'hasil_penunjang',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>
                        
                        

                        <div class="form-group">
                            <label class="control-label">Saran Pemeriksaan</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'saran_pemeriksaan',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div> 
                    </div>
                    <div class="more_adv tab-pane fade" id="tab_3">
                        <?= $form->field($model, 'alergi_obat')->textInput(['placeholder'=>'Isi Alergi Obat']) ?>
                        
                        <?= Html::button('<i class="fa fa-plus"></i> Obat Stok', ['value'=>Url::to(['inv-item-master/tambah-obat','tipe'=>'biasa']),'class' => 'btn green-haze btn-outline sbold uppercase modalWindow','type'=>'button']) ?>
                        <?= Html::button('<i class="fa fa-plus"></i> Obat Resep', ['type'=>'button','id'=>'tambah-resep','class' => 'btn green-haze btn-outline sbold uppercase']) ?>
                        
                        <br/>
                        <br/>
                        <table class="table table-hover table-light">
                            <thead>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Sinna</th>
                            </thead>
                            <tbody id="obat-rm">
                                <?php if(isset($data_exist['Obat'])): ?>
                                <?php foreach($data_exist['Obat']['jumlah'] as $obat_id => $jumlah): ?>
                                    <?php if($obat_id!='resep'): ?>
                                    <?php $obat = Obat::findOne(['obat_id'=>$obat_id]); ?> 
                                    <tr>
                                        <td><?= $obat['nama_merk']  ?></td>
                                        <td><input type="number" value="<?= $jumlah ?>" class="form-control" required="" placeholder="jumlah" name="Obat[jumlah][<?= $obat_id ?>]"></td>
                                        <td><?= $obat['satuan'] ?></td>
                                        <td><input type="text" value="<?= $data_exist['Obat']['signa'][$obat_id] ?>" class="form-control" required="" placeholder="signa" name="Obat[signa][<?= $obat_id ?>]"></td>
                                        <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if(isset($rm_obat)): ?>
                                <?php foreach($rm_obat as $key => $value): ?>
                                    <?php if(!empty($value['obat_id'])): ?>
                                    <tr>
                                        <td><?= $value['nama_obat']  ?></td>
                                        <td><input type="number" value="<?= $value['jumlah'] ?>" class="form-control" required="" placeholder="jumlah" name="Obat[jumlah][<?= $value['obat_id'] ?>]"></td>
                                        <td><?= $value['satuan']  ?></td>
                                        <td><input type="text" value="<?= $value['signa'] ?>" class="form-control" required="" placeholder="signa" name="Obat[signa][<?= $value['obat_id'] ?>]"></td>
                                        <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td><input value="<?= $value['nama_obat'] ?>" type="text" class="form-control" required="" placeholder="Nama" name="Obat[nama][resep][]"></td>
                                        <td><input type="number" value="<?= $value['jumlah'] ?>" class="form-control" required="" placeholder="jumlah" name="Obat[jumlah][resep][]"></td>
                                        <td><input type="text" value="<?= $value['satuan'] ?>" class="form-control" required="" placeholder="satuan" name="Obat[satuan][resep][]"></td>
                                        <td><input type="text" value="<?= $value['signa'] ?>" class="form-control" required="" placeholder="signa" name="Obat[signa][resep][]"></td>
                                        <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="more_adv tab-pane fade" id="tab_4">
                        <div id="rightCol">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-red-sunglo">
                                        <i class="icon-settings font-red-sunglo"></i>
                                        <span class="caption-subject bold uppercase">Obat Racik</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <?= Html::button('<i class="fa fa-plus"></i> Obat Stok', ['counter'=>1,'value'=>Url::to(['inv-item-master/tambah-obat','tipe'=>'racik']),'class' => 'btn green-haze btn-outline sbold uppercase modalWindow','type'=>'button']) ?>
                                    <?= Html::button('<i class="fa fa-plus"></i> Obat Resep', ['type'=>'button','class' => 'btn green-haze btn-outline sbold uppercase tambah-resep-racik','counter'=>1]) ?>
                                    <input type="hidden" value="1" />
                                    <?= Html::button('<i class="fa fa-plus"></i> Racikan', ['class' => 'btn purple-sharp btn-outline sbold uppercase tambahRacikan']) ?>
                                    
                                    <br/>
                                    <br/>
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <th>Nama Obat</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="obat-rm-racik-1">
                                            <?php if(isset($rm_obatracik[0])): ?>
                                            <?php if(isset($rm_obatracik_komponen[$rm_obatracik[0]['racik_id']])): ?>
                                            <?php foreach($rm_obatracik_komponen[$rm_obatracik[0]['racik_id']] as $key => $value): ?>
                                                <?php if(!empty($value['obat_id'])): ?>
                                                <tr>
                                                    <td><?= $value['nama_obat']  ?></td>
                                                    <td><input type="number" value="<?= $value['jumlah'] ?>" class="form-control" required="" placeholder="jumlah" name="ObatRacik[1][jumlah][<?= $value['obat_id'] ?>]"></td>
                                                    <td><?= $value['satuan']  ?></td>
                                                    <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                                </tr>
                                                <?php else: ?>
                                                <tr>
                                                    <td><input value="<?= $value['nama_obat'] ?>" type="text" class="form-control" required="" placeholder="Nama" name="ObatRacik[1][nama][resep][]"></td>
                                                    <td><input type="number" value="<?= $value['jumlah'] ?>" class="form-control" required="" placeholder="jumlah" name="ObatRacik[1][jumlah][resep][]"></td>
                                                    <td><input value="<?= $value['satuan'] ?>" type="text" class="form-control" required="" placeholder="Nama" name="ObatRacik[1][satuan][resep][]"></td>
                                                    <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                        </tbody>

                                    </table>

                                    <div class="form-group">
                                        <label class="control-label" for="pulf">M.F.</label>
                                        <input type="number" name="ObatRacik[1][jumlah_pulf]" value="<?= isset($rm_obatracik[0]['jumlah']) ? $rm_obatracik[0]['jumlah'] : "" ?>" id="pulf" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="signa_pulf">Signa</label>
                                        <input type="text" name="ObatRacik[1][signa]" value="<?= isset($rm_obatracik[0]['signa']) ? $rm_obatracik[0]['signa'] : "" ?>" id="signa_pulf" class="form-control">
                                    </div>
                                    
                                </div>
                            </div>

                            <?php if(isset($rm_obatracik)): ?>
                            <?php if(count($rm_obatracik)>1): ?>
                            <?php foreach($rm_obatracik as $key_racik=>$value_racik): ?>
                            <?php if($key_racik==0) continue; ?>
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase">Obat Racik</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <?= Html::button('<i class="fa fa-plus"></i> Obat Stok', ['value'=>Url::to(['inv-item-master/tambah-obat','tipe'=>'racik']),'class' => 'btn green-haze btn-outline sbold uppercase modalWindow','type'=>'button']).' '.Html::button('<i class="fa fa-plus"></i> Obat Resep', ['type'=>'button','class'=>'btn green-haze btn-outline sbold uppercase tambah-resep-racik','counter'=>$key_racik+2]) ?>
                                        <input type="hidden" value="<?= $key_racik+2 ?>" />
                                        <?= Html::button('<i class="fa fa-trash"></i> Hapus Racikan', ['class' => 'btn red-mint btn-outline sbold uppercase hapusRacikan']) ?>
                                        
                                        <br/>
                                        <br/>
                                        <table class="table table-hover table-light">
                                            <thead>
                                                <th>Nama Obat</th>
                                                <th>Jumlah</th>
                                                <th>Satuan</th>
                                                <th></th>
                                            </thead>
                                            <tbody id="obat-rm-racik-<?= $key_racik+2 ?>">
                                                <?php if(isset($rm_obatracik_komponen[$value_racik['racik_id']])): ?>
                                                <?php foreach($rm_obatracik_komponen[$value_racik['racik_id']] as $key => $value): ?>
                                                    <?php if(!empty($value['obat_id'])): ?>
                                                    <tr>
                                                        <td><?= $value['nama_obat']  ?></td>
                                                        <td><input type="number" value="<?= $value['jumlah'] ?>" class="form-control" required="" placeholder="jumlah" name="ObatRacik[<?= $key_racik+2 ?>][jumlah][<?= $value['obat_id'] ?>]"></td>
                                                        <td><?= $value['satuan']  ?></td>
                                                        <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                                    </tr>
                                                    <?php else: ?>
                                                    <tr>
                                                        <td><input value="<?= $value['nama_obat'] ?>" type="text" class="form-control" required="" placeholder="Nama" name="ObatRacik[<?= $key_racik+2 ?>][nama][resep][]"></td>
                                                        <td><input type="number" value="<?= $value['jumlah'] ?>" class="form-control" required="" placeholder="jumlah" name="ObatRacik[<?= $key_racik+2 ?>][jumlah][resep][]"></td>
                                                        <td><input value="<?= $value['satuan'] ?>" type="text" class="form-control" required="" placeholder="Nama" name="ObatRacik[<?= $key_racik+2 ?>][satuan][resep][]"></td>
                                                        <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>

                                        </table>

                                        <div class="form-group">
                                            <label class="control-label" for="pulf">M.F.</label>
                                            <input type="number" name="ObatRacik[<?= $key_racik+2 ?>][jumlah_pulf]" value="<?= isset($value_racik['jumlah']) ? $value_racik['jumlah'] : "" ?>" id="pulf" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="signa_pulf">Signa</label>
                                            <input type="text" name="ObatRacik[<?= $key_racik+2 ?>][signa]" value="<?= isset($value_racik['signa']) ? $value_racik['signa'] : "" ?>" id="signa_pulf" class="form-control">
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php endif; ?>
                            </div>
                        
                    </div>

                    <div class="more_adv tab-pane fade" id="tab_6">
                            
                                <?php 
                                    // A disabled select2 list input
                                    echo Select2::widget([
                                        'name' => 'lab_item',
                                        'value' => isset($rm_lab) ? $rm_lab : [],
                                        'data' => $data_lab,
                                        'options' => [
                                            'placeholder' => 'Pilih Laborat',
                                            'multiple' => true
                                        ]
                                    ]);
                                ?>
                                <br/>
                                <br/>
                    </div>

                    <div class="more_adv tab-pane fade" id="tab_7">
                        <?php 
                                    
                                    echo Select2::widget([
                                        'name' => 'rad_item',
                                        'value' => isset($rm_rad) ? $rm_rad : [],
                                        'data' => $data_rad,
                                        'options' => [
                                            'placeholder' => 'Pilih Rad',
                                            'multiple' => true
                                        ]
                                    ]);
                                ?>
                        <br/>
                        <br/>
                    </div>                    
                    <?php if ($kunjungan->eklaim!=false): ?>
                        <div class="more_adv tab-pane fade" id="tab_8">
                            <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($eklaim, 'adl_sub_acute')->textInput(['type'=>'number'])->input('adl_sub_acute', ['placeholder' => "12 - 60"]) ?>
                            </div>

                            <div class="col-md-4">
                                <?= $form->field($eklaim, 'adl_chronic')->textInput(['type'=>'number'])->input('adl_chronic', ['placeholder' => "12 - 60"]) ?>
                            </div>

                            <div class="col-md-4">
                                <?= $form->field($eklaim, 'icu_indikator')->dropDownList(Eklaim::icu_indikatorOPT()) ?>
                            </div>

                            <div class="col-md-4">
                                <?= $form->field($eklaim, 'icu_los')->textInput(['type'=>'number']) ?>
                            </div>

                            <div class="col-md-4">
                                <?= $form->field($eklaim, 'ventilator_hour')->textInput(['type'=>'number']) ?>
                            </div>

                            <div class="col-md-4">
                                <?= $form->field($eklaim, 'discharge_status')->dropDownList(
                                    Eklaim::discharge_statusOPT()
                                ) ?>
                            </div>

                            <div class="col-md-4">
                                <?php
                                $eklaim->procedure = $eklaim->procedures;
                                echo $form->field($eklaim, 'procedure')->widget(Select2::classname(), [
                                'options' => ['placeholder' => 'Pilih procedure', 'multiple' => true],
                                'initValueText' => $eklaim->proceduresText,
                                'value' => $eklaim->procedures,
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 2,
                                    'language' => [
                                        'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                                    ],
                                    'ajax' => [
                                        'url' => Url::to(['diagnosis/cari-procedure']),
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(dat) { return dat.text; }'),
                                    'templateSelection' => new JsExpression('function (dat) { return dat.text; }'),
                                ],
                            ]); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php $delBtnTindakan = Html::button('<i class="fa fa-trash"></i> Hapus', ['class' => 'btn red-mint btn-outline sbold uppercase hapusTindakan']);
                    // print_r($rm_tindakan) ?>
                    <!-- begin of tindakan -->
                    <div class="more_adv tab-pane fade" id="tab_9">
                        <div class="form-group">
                            <table id="listTindakan" class="table table-striped table-hover">
                                <thead>
                                    <th width="50%">Tindakan</th>
                                    <th width="20%">Dokter</th>
                                    <th width="5%">Jumlah</th>
                                    <th width="5%">Aksi</th>
                                </thead>
                                <thead>
                                    <th>
                                        <?= Select2::widget([
                                            'name' => 'tindakan[]',
                                            'options' => ['placeholder' => 'Pilih Tindakan'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'minimumInputLength' => 2,
                                                'language' => [
                                                    'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                                                ],
                                                'ajax' => [
                                                    'url' => Url::to(['tindakan/cari']),
                                                    'dataType' => 'json',
                                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                                ],
                                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                                'templateResult' => new JsExpression('function(dat) { return dat.text; }'),
                                                'templateSelection' => new JsExpression('function (dat) { return dat.text; }'),
                                            ],
                                        ]) ?>
                                </th>
                                    <th>
                                        <?= Select2::widget([
                                            'name' => 'dokter[]',
                                            'data'=>ArrayHelper::map(User::find()->select(['user.id as user_id','nama'])->leftJoin('dokter','user.id=dokter.user_id')->where(['role'=>25])->asArray()->all(),'user_id','nama'), 
                                            'value'=>(Yii::$app->user->identity->role==USER::ROLE_DOKTER)? Yii::$app->user->identity->id : "",
                                            'options' => ['placeholder' => 'Pilih Dokter'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'minimumInputLength' => 1,
                                                'language' => [
                                                    'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                                                ],
                                            ],
                                        ]) ?>
                                    </th>
                                    <th><?= Html::textInput('jumlah_tindakan','1',['class'=>'form-control jumlah_tindakan','type' => 'number'] ) ?></th>
                                    <th><?= Html::button('<i class="fa fa-plus"></i> Tambah', ['class' => 'addTindakan btn green-haze btn-outline sbold uppercase']) ?></th>
                                </thead>
                                <tbody>

                                    <?php if(!$model->isNewRecord)
                                        if(isset($rm_tindakan))
                                                foreach ($rm_tindakan as $key => $value): 
                                                    $lTindakan[] = $value->tindakan_cd;?>
                                                    <tr><td class="colTindakan"><input class="t" type="hidden" name="tindakan[listTindakan][]" value="<?= @$value->tindakan_cd ?>"><?= @$value->nama_tindakan ?></td><td><input type="hidden" name="tindakan[listDokter][]" value="<?= @$value->user_id ?>"><?= $value->dokter->dokter->nama ?></td><td><input type="hidden" name="tindakan[listJumlah][]" value="<?= @$value->jumlah ?>"><?= $value->jumlah ?></td><td><?= $delBtnTindakan ?></td></tr>
                                    <?php endforeach; ?>
                                   
                                </tbody>
                            </table>
                        </div>

                         <div class="form-group">
                            <label class="control-label">Deskripsi Tindakan</label>
                            <?= \yii\redactor\widgets\Redactor::widget([
                                'model' => $model,
                                'attribute' => 'deskripsi_tindakan',
                                'clientOptions'=>[
                                    'buttons' => []
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <!-- end of tindakan -->

                    <!-- begin of gizi -->
                    <?php $delBtnGizi = Html::button('<i class="fa fa-trash"></i> Hapus', ['class' => 'btn red-mint btn-outline sbold uppercase hapusGizi']);
                    // print_r($rm_tindakan) ?>
                    <?php if ($kunjungan->tipe_kunjungan=='Rawat Inap'){ ?>
                    <div class="more_adv tab-pane fade" id="tab_12">
                        <div class="form-group">
                            <table id="listDiet" class="table table-striped table-hover">
                                <thead>
                                    <th>Jenis Diet</th>
                                    <th>Jam Makan</th>
                                    <th>Jam Makan Spesifik</th>
                                    <th>Diagnosa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </thead>
                                <thead>
                                    <th>
                                        <?= Select2::widget([
                                            'name' => 'kd_gizi[]',
                                            'options' => ['placeholder' => 'Pilih Diet'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'minimumInputLength' => 1,
                                                'language' => [
                                                    'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                                                ],
                                                'ajax' => [
                                                    'url' => Url::to(['gizi-diet/cari']),
                                                    'dataType' => 'json',
                                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                                ],
                                                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                                'templateResult' => new JsExpression('function(dat) { return dat.text; }'),
                                                'templateSelection' => new JsExpression('function (dat) { return dat.text; }'),
                                            ],
                                        ]) ?>
                                    </th>
                                    <th>
                                        <?= Html::dropDownList('jam_makan[]',null,[
                                            'Pagi'=>'Pagi',
                                            'Siang'=>'Siang',
                                            'Sore'=>'Sore',
                                            'Lainnya'=>'Lainnya',
                                        ],['class'=>'jam_makan form-control']) ?>
                                    </th>
                                    <th><?= Html::textInput('jam_makan_spesifik[]','',['class'=>'form-control jam_makan_spesifik','placeholder'=>'hh:mm','disabled'=>'disabled']) ?></th>
                                    <th><?= Html::textInput('diagnosa_RI[]','',['class'=>'diagnosa_RI form-control']) ?></th>
                                    <th>-</th>
                                    <th><?= Html::button('<i class="fa fa-plus"></i> Tambah', ['class' => 'addGizi btn green-haze btn-outline sbold uppercase']) ?></th>
                                </thead>
                                <tbody>
                                    <?php if(!$model->isNewRecord)
                                                foreach ($modelRmInapGizi as $key => $value):?>
                                                    <tr><td class="colDiet"><input class="t" type="hidden" name="diet[listDiet][]" value="<?=$value->kode_diet ?>"><?=$value->diet->nama_diet ?></td><td><input type="hidden" name="diet[listJamMakan][]" value="<?=$value->jam_makan ?>"><?=$value->jam_makan ?></td><td><input type="hidden" name="diet[listJamMakanSpesifik][]" value="<?=$value->jam_makan_spesifik ?>"><?=$value->jam_makan_spesifik ?></td><td><input type="hidden" name="diet[listDiagnosa][]" value="<?=$value->diagnosa ?>"><?=$value->diagnosa ?></td><td><?=$value->status ?></td><td><?= $delBtnGizi ?></td></tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($kunjungan, 'catatan_keluar')->textArea(); ?>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- end of gizi -->

                    <?php 
                         //PERINATOLOGI
                         if ($kunjungan->tipe_kunjungan=='Rawat Inap' && floor((time()-strtotime($kunjungan->mr0->tanggal_lahir)) / (60 * 60 * 24))<=6): ?>
                            <div class="more_adv tab-pane fade" id="tab_11">
                                <?=  $this->render('_formPerinatologi', compact('form','modelRmInap','modelRmInapGizi','model','histori_rm','rm_diagnosis_id','rm_diagnosis_text','rm_diagnosis_banding_id','rm_diagnosis_banding_text','rm_tindakan','rm_obat','rm_obatracik','rm_obatracik_komponen','kunjungan','data_exist','data_rad','data_lab')) ?>
                            </div>
                        <?php endif; ?>

                    <?php 
                         //LAPORAN RL
                         if (in_array($kunjungan->medunit_cd, $poliHaveRl)): ?>
                            <div class="more_adv tab-pane fade" id="tab_10">
                                <?=  $this->render('_formRL', compact('form','modelRmInap','modelRmInapGizi','model','histori_rm','rm_diagnosis_id','rm_diagnosis_text','rm_diagnosis_banding_id','rm_diagnosis_banding_text','rm_tindakan','rm_obat','rm_obatracik','rm_obatracik_komponen','kunjungan','data_exist','data_rad','data_lab')) ?>
                            </div>
                        <?php endif; ?>
        
                </div>
                <?= Html::submitButton('Simpan', ['id'=>'simpan_submit','class' => 'btn blue','name'=>'Simpan']) ?>
                <?= ($kunjungan->tipe_kunjungan=='Rawat Jalan')?Html::button('Selesai Periksa', ['id'=>'selesai_submit','class' => 'btn red checkout']):Html::button('Selesaikan Rawat Inap', ['id'=>'selesai_submit','class' => 'btn red checkout']) ?>
            </div>
        </div>
    </div>
    
</div>


<?php ActiveForm::end(); ?>


<?php
$this->registerJsFile('@web/plugins/jquery.mask.min.js',['depends'=>'app\assets\MetronicAsset']);

$tombol_tambah = Html::button('<i class="fa fa-plus"></i> Obat Stok', ['value'=>Url::to(['inv-item-master/tambah-obat','tipe'=>'racik']),'class' => 'btn green-haze btn-outline sbold uppercase modalWindow','type'=>'button']).' '.Html::button('<i class="fa fa-plus"></i> Obat Resep', ['type'=>'button','class'=>'btn green-haze btn-outline sbold uppercase tambah-resep-racik']);
$tombol_hapus = Html::button('<i class="fa fa-trash"></i> Hapus Racikan', ['class' => 'btn red-mint btn-outline sbold uppercase hapusRacikan']);

$lTindakan = ($model->isNewRecord)?json_encode([]):json_encode((isset($lTindakan))?$lTindakan:[]);
$urlToMutasi = Url::to(['kunjungan/mutasi','id'=>$kunjungan->kunjungan_id,'to'=>'ranap']);
$script = <<< JS
    $('.jam_makan_spesifik').mask('99:99', {reverse: true});
    var tindakans = {$lTindakan};
    $(function(){

        $('.status_keluar').change(function(){
            if($(this).val()=='PULANG'){
                $('.status_keluar').hide();
                $('#checkoutContent').show();
            }
            else{
                $('#checkout').modal('hide');
                $('#modal').modal('show')
                .find('#modalContent')
                .load("{$urlToMutasi}");
            }
        })

        //begin of gizi
        $('.jam_makan').change(function(){
            if($('.jam_makan').val()!='Lainnya')
            {
                $('.jam_makan_spesifik').attr("disabled", "disabled");
                $('.jam_makan_spesifik').val('');
            }
            else
                $('.jam_makan_spesifik').removeAttr("disabled"); 
        })

        $('.addGizi').click(function(){
            var s2Diet = $('#w6').select2('data');
            var s2JamMakan = $('.jam_makan').val();
            var diagnosa_RI = $('.diagnosa_RI').val();
            var s2JamMakanSpesifik = $('.jam_makan_spesifik').val();

            if (s2Diet[0].id==''){
                alert('Pilih Diet Dulu.');
            } else {
                var row = '<tr><td class="colDiet"><input class="t" type="hidden" name="diet[listDiet][]" value="'+s2Diet[0].id+'">'+s2Diet[0].text+'</td><td><input type="hidden" name="diet[listJamMakan][]" value="'+s2JamMakan+'">'+s2JamMakan+'</td><td><input type="hidden" name="diet[listJamMakanSpesifik][]" value="'+s2JamMakanSpesifik+'">'+s2JamMakanSpesifik+'</td><td><input type="hidden" name="diet[listDiagnosa][]" value="'+diagnosa_RI+'">'+diagnosa_RI+'</td><td>-</td><td>{$delBtnGizi}</td></tr>';
                $('#listDiet').append(row);

                $("#w6").select2("val", "");

                $('.hapusGizi').click(function(){
                    var x = $(this).closest('tr').children('td.colDiet').find('.t').val();
                    $(this).closest('tr').remove();
                });
                $('.diagnosa_RI').val("");
                $('.jam_makan_spesifik').val("");
            }
        })


        $('.hapusGizi').click(function(){
            var x = $(this).closest('tr').children('td.colDiet').find('.t').val();
            $(this).closest('tr').remove();
        });

        //end of gizi

        $('.checkout').click(function(event){
            event.preventDefault();

            $('.status_keluar').val('');

            $('.status_keluar').show();
            $('#checkoutContent').hide();

            $('#checkout').modal('show')
        });

        //begin of tindakan
        $('.hapusTindakan').click(function(){
                    var x = $(this).closest('tr').children('td.colTindakan').find('.t').val();
                    tindakans.splice($.inArray(x, tindakans), 1);
                    $(this).closest('tr').remove();
                });

        $('.addTindakan').click(function(){
            var s2Tindakan = $('#w4').select2('data');
            var s2Dokter = $('#w5').select2('data');
            var jumlah = $('.jumlah_tindakan').val();

            if (s2Tindakan[0].id=='' || s2Dokter[0].id==''|| jumlah==''|| jumlah=='0'){
                alert('Pilih Tindakan, Dokter dan jumlah Dulu.');
            // } else if(jQuery.inArray(s2Tindakan[0].id, tindakans) === -1){
            } else{
                tindakans.push(s2Tindakan[0].id);
                var row = '<tr><td class="colTindakan"><input class="t" type="hidden" name="tindakan[listTindakan][]" value="'+s2Tindakan[0].id+'">'+s2Tindakan[0].text+'</td><td><input type="hidden" name="tindakan[listDokter][]" value="'+s2Dokter[0].id+'">'+s2Dokter[0].text+'</td><td><input type="hidden" name="tindakan[listJumlah][]" value="'+jumlah+'">'+jumlah+'</td><td>{$delBtnTindakan}</td></tr>';
                $('#listTindakan').append(row);

                $("#w4").select2("val", "");
                $("#w5").select2("val", "");
                $('.jumlah_tindakan').val("1");

                $('.hapusTindakan').click(function(){
                    var x = $(this).closest('tr').children('td.colTindakan').find('.t').val();
                    tindakans.splice($.inArray(x, tindakans), 1);
                    $(this).closest('tr').remove();
                });
            }
            // else if(jQuery.inArray(s2Tindakan[0].id, tindakans) !== -1){
            //     alert('Tindakan telah terdaftar sebelumnya.');
            // }
        })
        //end of tindakan
        function icu_indi(){
            if ($('#kunjunganeklaim-icu_indikator').val()=='0'){
                $('#kunjunganeklaim-icu_los').val('0');
                $('#kunjunganeklaim-ventilator_hour').val('0');
            }
        }
        icu_indi();
        $('#kunjunganeklaim-icu_indikator').change(function(){
            icu_indi();
        });
        $('#useDefault').click(function(){
            var t = $(this).is(':checked');
            $('.tekanan_darah').val('120/80');
            $('.nadi').val('80');
            $('.respirasi_rate').val('20');
            $('.suhu').val('36');

            $('.tekanan_darah').prop("readonly",t);
            $('.nadi').prop("readonly",t);
            $('.respirasi_rate').prop("readonly",t);
            $('.suhu').prop("readonly",t);
        });
        // $( "#useDefault" ).trigger( "click" );


        $('#form-rm button:submit').click(function() {
            $('#form-rm').submit();
            $(this).attr('disabled', true);
        });

        $('#tambah-resep').click(function(){
            str_obat = "<tr>";
            str_obat += "<td><input type='text' class='form-control' required placeholder='Nama' name='Obat[nama][resep][]'></td>";
            str_obat += "<td><input type='number' class='form-control' required placeholder='jumlah' name='Obat[jumlah][resep][]'></td>";
            str_obat += "<td><input type='text' class='form-control' required placeholder='satuan' name='Obat[satuan][resep][]'></td>";
            str_obat += "<td><input type='text' class='form-control' required placeholder='signa' name='Obat[signa][resep][]'></td>";
            str_obat += "<td><button type='button' class='btn btn-danger delete-item'>x</button></td>";
            str_obat += "</tr>";

            $('#obat-rm').append(str_obat);

            $('.delete-item').click(function(){
                $(this).parent().parent().remove()
            })
        })
        tambahResepRacik();
        function tambahResepRacik(){
            $('.tambah-resep-racik').click(function(){
                var c = $(this).attr('counter')
                if(c==undefined){
                    c = $(this).parent().next().html();
                    console.log(c)
                }
                str_obat = "<tr>";
                str_obat += "<td><input type='text' class='form-control' required placeholder='Nama' name='ObatRacik["+c+"][nama][resep][]'></td>";
                str_obat += "<td><input type='number' class='form-control' required placeholder='jumlah' name='ObatRacik["+c+"][jumlah][resep][]'></td>";
                str_obat += "<td><input type='text' class='form-control' required placeholder='satuan' name='ObatRacik["+c+"][satuan][resep][]'></td>";
                str_obat += "<td><button type='button' class='btn btn-danger delete-item'>x</button></td>";
                str_obat += "</tr>";
                $(this).parent().find('table').append(str_obat);

                $('.delete-item').click(function(){
                    $(this).parent().parent().remove()
                })
            })
        }
        
        function hitungBmi(){
            var bb = parseFloat($('#rekammedis-berat_badan').val());
            var tb = parseFloat($('#rekammedis-tinggi_badan').val());
            var bmi = bb / ((tb/100)*(tb/100))
            $('#bmi_hasil').html(bmi.toFixed(2));
        }
        hitungBmi();

        $('#rekammedis-berat_badan').keyup(function(){
            hitungBmi();
        })

        $('.delete-item').click(function(){
            $(this).parent().parent().remove()
        })

        $('#rekammedis-tinggi_badan').keyup(function(){
            hitungBmi();
        })

        var racik_counter = {$start_counter};
        $('.modalWindow').click(function(){
            var r = $(this).next().next().val()
            $('#modal').modal('show')
                .find('#modalContent')
                .load($(this).attr('value')+"&counter="+r)
        })
        $('.hapusRacikan').click(function(){
            $(this).parent().parent().remove();
        })
        $('.tambahRacikan').click(function(){
            racik_counter += 1;
            var str ='<div class="portlet light bordered"><div class="portlet-title"><div class="caption font-red-sunglo"><i class="icon-settings font-red-sunglo"></i><span class="caption-subject bold uppercase">Obat Racik</span></div></div><div class="portlet-body form"> {$tombol_tambah} <input type="hidden" value="'+racik_counter+'" /> {$tombol_hapus}<br/> <br/> <table class="table table-hover table-light"> <thead> <th>Nama Obat</th> <th>Jumlah</th><th>Satuan</th> </thead> <tbody id="obat-rm-racik-'+racik_counter+'"> </tbody> </table> <div class="form-group"> <label class="control-label" for="pulf">M.F.</label> <input type="number" name="ObatRacik['+racik_counter+'][jumlah_pulf]" id="pulf" class="form-control"> </div> <div class="form-group"> <label class="control-label" for="signa_pulf">Signa</label> <input type="text" name="ObatRacik['+racik_counter+'][signa]" id="signa_pulf" class="form-control"> </div></div><span style="display:none">'+racik_counter+'</span></div>';
            $('#rightCol').append(str);
            tambahResepRacik();
            $('.modalWindow').click(function(){
                var r = $(this).next().next().val()
                $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value')+"&counter="+r)
            })
            $('.hapusRacikan').click(function(){
                $(this).parent().parent().remove();
            })
        })
    });

JS;

$this->registerJs($script);
?>
<?php
//if ($kunjungan->tipe_kunjungan=='Rawat Inap' && !$model->isNewRecord)
//echo $this->render('_formRawatInap', compact('modelRmInap','modelRmInapGizi','model','histori_rm','rm_diagnosis_id','rm_diagnosis_text','rm_diagnosis_banding_id','rm_diagnosis_banding_text','rm_tindakan','rm_obat','rm_obatracik','rm_obatracik_komponen','kunjungan','data_exist','data_rad','data_lab')) ?>