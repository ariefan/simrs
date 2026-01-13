<?php
use app\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Pasien;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model app\models\RmLab */
/* @var $form yii\widgets\ActiveForm */
?>
<div>
	<h4>Data Pasien</h4>
	<?= $pasien->nama ?> <br/>
	<?= $pasien->getAge($pasien->tanggal_lahir) ?> Tahun  <br/>
	<?= $pasien->jk ?> <br/>
	<?= $pasien->alamat ?> <br/>
</div>
<hr/>
<div class="rm-lab-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php $delBtnTindakan = Html::button('<i class="fa fa-trash"></i> Hapus', ['class' => 'btn red-mint btn-outline sbold uppercase hapusTindakan']);
    // print_r($rm_tindakan) ?>
    <!-- begin of tindakan -->
    <div class="form-group">
        <table id="listTindakan" class="table table-striped table-hover">
            <thead>
                <th width="50%">Jenis Pemeriksaan</th>
                <th width="20%">Dokter</th>
                <th width="5%">Jumlah</th>
                <th width="5%">Aksi</th>
            </thead>
            <thead>
                <th>
                    <?= Select2::widget([
                        'name' => 'tindakan[]',
                        'data' => $item_lab,
                        'options' => ['placeholder' => 'Pilih Jenis Pemeriksaan Lab.'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 2,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Mencari...'; }"),
                            ],
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
                    if(isset($data_lab))
                            foreach ($data_lab as $key => $value):?>
                                <tr><td class="colTindakan"><input class="t" type="hidden" name="tindakan[listTindakan][]" value="<?= @$value->medicalunit_cd ?>"><?= @$value->nama ?></td><td><input type="hidden" name="tindakan[listDokter][]" value="<?= @$value->dokter ?>"><?= $value->dokter_nama ?></td><td><input type="hidden" name="tindakan[listJumlah][]" value="<?= @$value->jumlah ?>"><?= $value->jumlah ?></td><td><?= $delBtnTindakan ?></td></tr>
                <?php endforeach; ?>
               
            </tbody>
        </table>
    </div>
        <table class="table table-bordered">
    	<thead>
    		<tr>
	    		<th>Jenis Pemeriksaan</th>
	    		<th>Jlh.</th>
                <th>Catatan</th>
                <th>Hasil</th>
	    		<th>File</th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php foreach($data_lab as $val): ?>
    		<tr>
                <td><?= $val['jenis']['medicalunit_nm'] ?></td>
    			<td><?= $val['jumlah'] ?></td>
    			<td><input type="text" class="form-control" value="<?= $val['catatan'] ?>" name="catatan[<?= $val['id'] ?>]"></td>
    			<td><input type="text" class="form-control" value="<?= $val['hasil'] ?>" name="hasil[<?= $val['id'] ?>]"></td>
                <td>
                    <?php if(!empty($val['hasil_file'])): ?>
                    <?= Html::img('@web/rm_penunjang/'.$val['hasil_file'],['width'=>'100px']) ?>
                    <?php endif; ?>
                    <input id="uploadImage" type="file" accept="image/*" name="gambar[<?= $val['id'] ?>]" />
                </td>
    		</tr>
    		<?php endforeach; ?>
    	</tbody>
    </table>
    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary','name'=>'Simpan']) ?>
        <?= Html::submitButton('Simpan dan Kembali', ['class' => 'btn btn-success','name'=>'SimpanKembali']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php

$script = <<< JS
//begin of tindakan
$('.hapusTindakan').click(function(){
            var x = $(this).closest('tr').children('td.colTindakan').find('.t').val();
            $(this).closest('tr').remove();
        });

$('.addTindakan').click(function(){
    var s2Tindakan = $('#w1').select2('data');
    var s2Dokter = $('#w2').select2('data');
    var jumlah = $('.jumlah_tindakan').val();

    if (s2Tindakan[0].id=='' || s2Dokter[0].id==''|| jumlah==''|| jumlah=='0'){
        alert('Pilih Tindakan, Dokter dan jumlah Dulu.');
    } else{
        var row = '<tr><td class="colTindakan"><input class="t" type="hidden" name="tindakan[listTindakan][]" value="'+s2Tindakan[0].id+'">'+s2Tindakan[0].text+'</td><td><input type="hidden" name="tindakan[listDokter][]" value="'+s2Dokter[0].id+'">'+s2Dokter[0].text+'</td><td><input type="hidden" name="tindakan[listJumlah][]" value="'+jumlah+'">'+jumlah+'</td><td>{$delBtnTindakan}</td></tr>';
        $('#listTindakan').append(row);

        $("#w1").select2("val", "");
        $("#w2").select2("val", "");
        $('.jumlah_tindakan').val("1");

        $('.hapusTindakan').click(function(){
            var x = $(this).closest('tr').children('td.colTindakan').find('.t').val();
            $(this).closest('tr').remove();
        });
    }
})
//end of tindakan
JS;

$this->registerJs($script);
?>