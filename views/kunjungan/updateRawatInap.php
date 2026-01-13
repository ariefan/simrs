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
$this->params['breadcrumbs'][] = ['label' => 'Kunjungan', 'url' => ['index']];
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
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Simpan Perubahan Data', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>



</div>
