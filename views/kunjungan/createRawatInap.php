<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\UnitMedis;
use app\models\Ruang;
use app\models\Kelas;
use app\models\User;
use app\models\JenisKunjungan;
use app\models\CaraBayar;
use app\models\AsalPasien;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\Kunjungan */

$this->title = 'Pendaftaran';
$this->params['breadcrumbs'][] = ['label' => 'Kunjungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunjungan-create">


<div class="kunjungan-form">

    <?php $form = ActiveForm::begin(["id"=>"form-pasien"]); ?>

    <div class="row">
    <div class="col-md-6">
	    <?= $form->field($model, 'ruang_cd')->widget(Select2::classname(), [
	        'data' =>  ArrayHelper::map(Ruang::find()->select(['ruang_cd', 'ruang_nm'])->where(['status'=>0])->asArray()->all(), 'ruang_cd', 'ruang_nm'),
	        'options' => ['placeholder' => 'Pilih Ruang Ranap'],
	        'pluginOptions' => [
	            'allowClear' => true
	        ],
	    ]); ?>
	</div>
	<div class="col-md-6">
	    <?= $form->field($model, 'kelas_cd')->widget(Select2::classname(), [
	        'data' =>  ArrayHelper::map(Kelas::find()->select(['kelas_cd', 'kelas_nm'])->asArray()->all(), 'kelas_cd', 'kelas_nm'),
	        'options' => ['placeholder' => 'Kosongkan apabila menyesuaikan dengan kelas ruang'],
	        'pluginOptions' => [
	            'allowClear' => true
	        ],
	    ]); ?>
	</div>
	<div class="col-md-6">
      <?= $form->field($model, 'dpjp')->widget(Select2::classname(), [
              'data'=>ArrayHelper::map(User::find()->select(['user.id as user_id','nama'])->leftJoin('dokter','user.id=dokter.user_id')->where(['role'=>25])->asArray()->all(),'user_id','nama'), 
              'value'=>(Yii::$app->user->identity->role==USER::ROLE_DOKTER)? Yii::$app->user->identity->id : "",
              'options' => ['placeholder' => 'Pilih Dokter'],
          ]); ?>
      </div>
	<div class="col-md-6">
	    <?= $form->field($model, 'rl_31')->widget(Select2::classname(), [
	        'data' =>  ArrayHelper::map(\Yii::$app->db->createCommand('SELECT * FROM rl_ref_31')->queryAll(), 'no', 'jenis_pelayanan'),
	        'options' => ['placeholder' => 'Harap diisi untuk kepentingan RL'],
	        'pluginOptions' => [
	            'allowClear' => true
	        ],
	    ]); ?>
	</div>

      <div class="col-md-4">
         <?= $form->field($model, 'jns_kunjungan_id')->dropDownList(
            ArrayHelper::map(JenisKunjungan::find()->all(), 'jns_kunjungan_id', 'jns_kunjungan_nama')
          ) ?>
      </div>
      <div class="col-md-4">
        <?= $form->field($model, 'cara_id')->dropDownList(
          ArrayHelper::map(CaraBayar::find()->all(), 'cara_id', 'cara_nama')
        ) ?>
      </div>
      <div class="col-md-4">
        <?= $form->field($model, 'asal_id')->dropDownList(
          ArrayHelper::map(Asalpasien::find()->all(), 'asal_id', 'asal_nama')
        ) ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="cari-pasien">No Rekam Medis/Nama</label>
          <input type="text" placeholder="Ketik No Rekam Medis/Nama..." class="form-control" id="cari-pasien">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="cari-pasien">Tgl. Lahir</label>
          <?= MaskedInput::widget([
              'name' => 'cari-pasien-ttl',
              'options' => [
                  'placeholder' => 'dd-mm-yyyy',
                  'class' => 'form-control',
              ],
              'id' => 'cari-pasien-ttl',
              'mask' => '99-99-9999',
          ]); ?>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="control-label" for="cari-pasien">Alamat</label>
          <input type="text" placeholder="Ketik Alamat..." class="form-control" id="cari-pasien-alamat">
        </div>
      </div>
    
    </div>
    
    <div class="form-group" id="hasil-pasien">
        Tekan Enter Pada Text Box
    </div>



    <?= Html::hiddenInput('mr','',['id'=>'mr-pasien']) ?>


    <?php ActiveForm::end(); ?>

</div>


<?php
$urlCari = Url::to(['kunjungan/cari-pasien']);
$script = <<< JS
    $(function(){

        $('#cari-pasien').keydown(function(e){
          if(e.keyCode == 13) {
            cariPasien();
          }
        });

        $('#cari-pasien-alamat').keydown(function(e){
          if(e.keyCode == 13) {
            cariPasien();
          }
        });

        $('#cari-pasien-ttl').keydown(function(e){
          if(e.keyCode == 13) {
            cariPasien();
          }
        });

        function cariPasien(){
            var key_1 = $('#cari-pasien').val();
            var key_2 = $('#cari-pasien-alamat').val();
            var key_3 = $('#cari-pasien-ttl').val();
            $('#hasil-pasien').html('<h1>Loading.....</h1>');

            $.post('{$urlCari}',{keyword:key_1,keyword_2:key_2,keyword_3:key_3})
            .done(function(data){
              str_hasil = "<table class='table table-bordered'>";
              str_hasil += "<thead><tr><th>No RM</th><th>Nama</th><th>Tgl. Lahir</th><th>Alamat</th><th></th></tr>";
              str_hasil += "<tbody>";
              data = JSON.parse(data);
              for(var i in data){
                str_hasil += "<tr><td>"+data[i].mr+"</td><td>"+data[i].nama+"</td><td>"+data[i].tanggal_lahir+"</td><td>"+data[i].alamat+"</td><td><button type='button' class='pilih-pasien btn btn-primary' value='"+data[i].mr+"'>Pilih</button>  </td></tr>"
              }
              str_hasil += "</tbody>";
              str_hasil += "</table>";
              $('#hasil-pasien').html(str_hasil);
              $('#form-pasien button:button').click(function() {
                  $(this).attr('disabled', true);
              });
              $('.pilih-pasien').on('click',function(){
                    $('#mr-pasien').val($(this).val());
                    $('#form-pasien').yiiActiveForm('validate');
                    $('#form-pasien').yiiActiveForm('submitForm');
              })
            });
        }

        $(window).keydown(function(event){
          if(event.keyCode == 13) {
            event.preventDefault();
            return false;
          }
        });

    });

JS;

$this->registerJs($script);
?>


</div>
