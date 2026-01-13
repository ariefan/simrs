<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Asuransi;
use app\models\Region;
use app\models\Pasien;
use app\models\RefSuku;
use app\models\Klinik;
use dosamigos\datepicker\DatePicker;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\db\Query;


/* @var $this yii\web\View */
/* @var $model app\models\Pasien */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="pasien-form">

<div class="col-md-15 col-sm-15">
<div class="portlet light bordered">
<div class="portlet-title">
    <div class="caption caption-md">
        <i class="icon-bar-chart font-blue"> Data Utama Pasien </i>
        <span class="caption-subject font-red bold uppercase"></span>
        <span class="caption-helper"></span>
    </div>
<div class="portlet-body">
<div class="table-scrollable table-scrollable-borderless">
    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'mr')->textInput(['value' => (($model->isNewRecord) ? $model->generateKode_Pasien(): $model->mr)] ) ?>

    <?= $form->field($model, 'no_identitas')->textInput(['maxlength' => true]) ?>

    <button id="btn_cari" url="<?= Url::to(['dukcapil']) ?>" class="btn btn-default" type="button">Cari</button>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tempat_lahir')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_lahir')->widget(
        DatePicker::className(),[
            'inline'=>false,
            //'template'=>'<div class="well well-sm" style="background-color: #fff; width: 250px">{input}</div>',
            'clientOptions'=>[
                'autoclose'=>true,
                'format'=>'yyyy-mm-dd'
            ]
        ]);?>

    <?= $form->field($model, 'jk')->dropDownList(['Pilih Jenis Kelamin: '=>[ 'Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan']]) ?>

    <?= $form->field($model, 'identitas')->dropDownList([ 'KTP' => 'Kartu Tanda Penduduk (KTP)', 'KK' => 'Kartu Keluarga (KK)', 'SIM'=>'Surat Ijin Mengemudi (SIM)', 'PASPOR'=>'PASPOR', 'KITAS'=>'KITAS',], ['prompt' => 'Pilih Jenis Identitas']);
    ?>


    <?= $form->field($model, 'jenis_asuransi')->dropDownList(ArrayHelper::map(Asuransi::find()->all(), 'insurance_cd', 'insurance_nm'), 
        ['prompt'=>'Silahkan Pilih Asuransi...']
        )  
    ?>
    <?= $form->field($model, 'no_asuransi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'warga_negara')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

</div>
</div>   
</div>
</div>
</div>

<div class="col-md-15 col-sm-15">
<div class="portlet light bordered">
<div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-blue"> Wilayah Domisili Pasien </i>
                    <span class="caption-subject font-red bold uppercase"></span>
                    <span class="caption-helper"></span>
                </div>
<div class="portlet-body">
<div class="table-scrollable table-scrollable-borderless">
    <?= $form->field($model, 'region_cd')->dropDownList(ArrayHelper::map(Region::find()->where(['region_root'=>''])->all(), 'region_cd', 'region_nm'), 
        ['id'=>'provinsi-id', 'prompt'=>'Silahkan Pilih Provinsi...']
        );  
    ?>

    <?= $form->field($model, 'region_cd')->widget(DepDrop::classname(), [
            'options'=>['id'=>'kota-id'],
            'pluginOptions'=>[
                'depends'=>['provinsi-id'],
                'placeholder'=>'Pilih Kota/Kabupaten...',
                'url'=>Url::to(['pasien/kokab'])
            ]
        ]); ?>

    <?= $form->field($model, 'region_cd')->widget(DepDrop::classname(), [
            'options'=>['id'=>'kec-id'],
            'pluginOptions'=>[
                'depends'=>['provinsi-id','kota-id'],
                'placeholder'=>'Pilih Kecamatan...',
                'url'=>Url::to(['pasien/kecamatan'])
            ]
        ]); ?>

    <?= $form->field($model, 'region_cd')->widget(DepDrop::classname(), [
            'options'=>['id'=>'kel-id'],
            'pluginOptions'=>[
                'depends'=>['provinsi-id','kota-id', 'kec-id'],
                'placeholder'=>'Pilih Kelurahan...',
                'url'=>Url::to(['pasien/kelurahan'])
            ]
        ]); ?>

</div>
</div>   
</div>
</div>
</div>

<div class="col-md-15 col-sm-15">
<div class="portlet light bordered">
<div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-blue"> Detail Domisili dan Kontak Pasien </i>
                    <span class="caption-subject font-red bold uppercase"></span>
                    <span class="caption-helper"></span>
                </div>
<div class="portlet-body">
<div class="table-scrollable table-scrollable-borderless">
    <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'kode_pos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_telp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

</div>
</div>   
</div>
</div>
</div>

<div class="col-md-15 col-sm-15">
<div class="portlet light bordered">
<div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-blue"> Pendidikan, Pekerjaan, Suku dan Agama Pasien </i>
                    <span class="caption-subject font-red bold uppercase"></span>
                    <span class="caption-helper"></span>
                </div>
<div class="portlet-body">
<div class="table-scrollable table-scrollable-borderless">
    <?= $form->field($model, 'pendidikan')->dropDownList([ 'SD' => 'SD', 'SMP' => 'SMP', 'SMA'=>'SMA', 'D3'=>'D3', 'S1/D4'=>'S1/D4', 'S2'=>'S2', 'S3'=>'S3','LAIN-LAIN'=>'LAIN-LAIN',], 
        ['prompt' => 'Pilih Tingkat Pendidikan']) 
    ?>

    <?php //echo $form->field($model, 'pekerjaan')->dropDownList([ 'PNS' => 'PNS', 'TNI' => 'TNI', 'POLRI'=>'POLRI', 'GURU'=>'GURU', 'DOSEN'=>'DOSEN', 'PELAJAR'=>'PELAJAR', 'MAHASISWA'=>'MAHASISWA','PETANI'=>'PETANI','NELAYAN'=>'NELAYAN', 'BURUH'=>'BURUH', 'SWASTA'=>'SWASTA', 'WIRASWASTA'=>'WIRASWASTA', 'SEKTOR INFORMAL'=>'SEKTOR INFORMAL', 'IBU RUMAH TANGGA'=>'IBU RUMAH TANGGA', 'PENSIUN'=>'PENSIUN', 'LAIN-LAIN'=>'LAIN-LAIN',], 
        // ['prompt' => 'Pilih Jenis Pekerjaan']) 
    ?>

    <?= $form->field($model, 'pekerjaan')->dropDownList([ 'PNS' => 'PNS', 'TNI' => 'TNI', 'POLRI'=>'POLRI', 'GURU'=>'GURU', 'DOSEN'=>'DOSEN','PELAJAR'=>'PELAJAR', 'MAHASISWA'=>'MAHASISWA','PETANI'=>'PETANI','NELAYAN'=>'NELAYAN','BURUH'=>'BURUH','SWASTA'=>'SWASTA','WIRASWASTA'=>'WIRASWASTA','SEKTOR INFORMAL'=>'SEKTOR INFORMAL','IBU RUMAH TANGGA'=>'IBU RUMAH TANGGA','PENSIUN'=>'PENSIUN',
        'LAIN - LAIN'=>'LAIN - LAIN',], 
        ['prompt' => 'Pilih pekerjaan']) 
    ?>


    <?php //echo $form->field($model, 'suku')->dropDownList([ 'MELAYU' => 'MELAYU', 'JAWA' => 'JAWA', 'BATAK'=>'BATAK', 'SUNDA'=>'SUNDA', 'LAIN-LAIN'=>'LAIN-LAIN',], 
        // ['prompt' => 'Pilih Suku']) 
    ?>


    <?= $form->field($model, 'suku')->dropDownList(
        ArrayHelper::map(Refsuku::find()->all(), 'suku_id', 'suku_nama'), 
        ['prompt'=>'Silahkan Pilih suku...']);  
    ?>


    <?= $form->field($model, 'agama')->dropDownList([ 'ISLAM' => 'ISLAM', 'KATOLIK' => 'KATOLIK', 'PROTESTAN'=>'PROTESTAN', 'HINDU'=>'HINDU', 'BUDHA'=>'BUDHA','KONGHUCU'=>'KONGHUCU', 'ALIRAN KEPERCAYAAN'=>'ALIRAN KEPERCAYAAN',], 
        ['prompt' => 'Pilih Agama']) 
    ?>
</div>
</div>   
</div>
</div>
</div>

<div class="col-md-15 col-sm-15">
<div class="portlet light bordered">
<div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-blue"> Data Detail Diri Pasien </i>
                    <span class="caption-subject font-red bold uppercase"></span>
                    <span class="caption-helper"></span>
                </div>
<div class="portlet-body">
<div class="table-scrollable table-scrollable-borderless">
    <?= $form->field($model, 'gol_darah')->dropDownList([ 'A' => 'A', 'B' => 'B', 'AB'=>'AB', 'O'=>'O', ], 
        ['prompt' => 'Pilih Golongan Darah']) 
    ?>    

    <?= $form->field($model, 'berat')->textInput() ?>

    <?= $form->field($model, 'tinggi')->textInput() ?>

    <?= $form->field($model, 'nama_ayah')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_ibu')->textInput(['maxlength' => true]) ?>
</div>
</div>   
</div>
</div>
</div>

<div class="col-md-15 col-sm-15">
<div class="portlet light bordered">
<div class="portlet-title">
                <div class="caption caption-md">
                    <i class="icon-bar-chart font-blue"> Penanggung Jawab Pasien </i>
                    <span class="caption-subject font-red bold uppercase"></span>
                    <span class="caption-helper"></span>
                </div>
<div class="portlet-body">
<div class="table-scrollable table-scrollable-borderless">
    <?= $form->field($model, 'pj_nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pj_hubungan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pj_alamat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pj_telpon')->textInput(['maxlength' => true]) ?>
</div>
</div>   
</div>
</div>
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Simpan Data Baru' : 'Simpan Perubahan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
$(function(){

    
    $('#btn_cari').click(function(){
        var identitas = $('#pasien-no_identitas').val()
        $.post( $(this).attr('url'), { nik: identitas })
          .done(function( data ) {
            data = JSON.parse(JSON.parse(data));
            $('#pasien-nama').val("KTP");
            $('#pasien-nama').val(data.content[0].NAMA_LGKP);
            $('#pasien-tanggal_lahir').val(data.content[0].TGL_LHR);
            $('#pasien-tempat_lahir').val(data.content[0].TMPT_LHR);
            $('#pasien-alamat').val(data.content[0].ALAMAT+" RT "+data.content[0].NO_RT+" RW "+data.content[0].RW+", KELURAHAN "+data.content[0].NAMA_KEL+", KECAMATAN "+data.content[0].NAMA_KEC+", KABUPATEN/KOTA "+data.content[0].NAMA_KAB+", PROPINSI "+data.content[0].NAMA_PROP);
            $('#provinsi-id').val(data.content[0].NO_PROP);
            $('#pasien-agama').val(data.content[0].AGAMA);
            $('#pasien-nama_ayah').val(data.content[0].NAMA_LGKP_AYAH);
            $('#pasien-pj_nama').val(data.content[0].NAMA_LGKP_AYAH);
            $('#pasien-nama_ibu').val(data.content[0].NAMA_LGKP_IBU);

            $('#provinsi-id').trigger('change');
          });
    })
    

});
JS;

$this->registerJs($script);
?>