<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use app\models\RefCaraPulang;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\RekamMedis */

$this->title = 'Coding';
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
<?php $form = ActiveForm::begin(['id'=>'form-rm']); ?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
                        
            <div class="portlet-body">
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
                    <?php
                    echo '<label class="control-label">Coding Tindakan (ICD-9)</label>';
                    echo Select2::widget([
                        'name' => 'icd_9',
                        'options' => ['placeholder' => 'Pilih ICD-9', 'multiple' => true],
                        'initValueText' => isset($rm_icd9_text) ? $rm_icd9_text : [],
                        'value' => isset($rm_icd9_id) ? $rm_icd9_id : [],
                        'pluginOptions' => [
                            'tags' => false,
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                            ],
                            'ajax' => [
                                'url' => Url::to(['tindakan/icd9']),
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
                
                <?= $form->field($model_kunjungan, 'jenis_keluar')->dropDownList(ArrayHelper::map(RefCaraPulang::find()->all(),'pulang_id','pulang_nama')) ?>
                <?= $form->field($model_kunjungan, 'catatan_keluar')->textArea(); ?>
                <?= $form->field($model_kunjungan, 'jam_selesai')->widget(\kartik\datecontrol\DateControl::className(), [
                    'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
                    'saveFormat' => 'php:Y-m-d H:i:s',
                    'ajaxConversion' => true,
                    'options' => [
                        'pluginOptions' => [
                            'placeholder' => 'Jam Selesai',
                            'autoclose' => true
                        ]
                    ]
                ]); ?>
                <div class="form-group">
                    <?= Html::submitButton('Simpan', ['class' => 'btn blue']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

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
                    <span class="profile-desc-text"> <?= isset($user_m->nama) ? $user_m->nama : "--Belum Melengkapi Nama--" ?> </span>
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
                                                <li><?= $value['nama_tindakan'] ?></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tindakan</th>
                                        <td>
                                            <ul>
                                            <?php foreach($rm_icd9 as $value): ?>
                                                <li><?= $value['short_desc'] ?></li>
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
                                            <ul>
                                            <?php foreach($rm_lab as $value): ?>
                                                <li><?= $value['nama'] ?></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Radiologi</th>
                                        <td>
                                            <ul>
                                            <?php foreach($rm_rad as $value): ?>
                                                <li><?= $value['nama'] ?></li>
                                            <?php endforeach; ?>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END PORTLET -->
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
