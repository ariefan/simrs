<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use app\models\User;
use app\models\UnitMedis;
use app\models\JenisKunjungan;
use app\models\CaraBayar;
use app\models\AsalPasien;

/* @var $this yii\web\View */
/* @var $model app\models\Kunjungan */

$this->title = 'Update Data Kunjungan ';
$this->params['breadcrumbs'][] = ['label' => 'Kunjungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kunjungan_id, 'url' => ['view', 'id' => $model->kunjungan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kunjungan-update">

    <?php $form = ActiveForm::begin(["id"=>"form-pasien"]); ?>

    <div class="row">
      <div class="col-md-6">
        <?= $form->field($model, 'medunit_cd')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(UnitMedis::find()->all(), 'medunit_cd', 'medunit_nm'),
            'options' => ['placeholder' => 'Pilih Poli / Unit...'],
            
        ]);
        ?>
      </div>
      <div class="col-md-6">
      <?= $form->field($model, 'dpjp')->widget(Select2::classname(), [
              'data'=>ArrayHelper::map(User::find()->select(['user.id as user_id','nama'])->leftJoin('dokter','user.id=dokter.user_id')->where(['role'=>25])->asArray()->all(),'user_id','nama'), 
              'value'=>(Yii::$app->user->identity->role==USER::ROLE_DOKTER)? Yii::$app->user->identity->id : "",
              'options' => ['placeholder' => 'Pilih Dokter'],
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
