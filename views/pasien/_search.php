<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PasienSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pasien-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mr') ?>

    <?= $form->field($model, 'klinik_id') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'identitas') ?>

    <?= $form->field($model, 'no_identitas') ?>

    <?php // echo $form->field($model, 'region_cd') ?>

    <?php // echo $form->field($model, 'warga_negara') ?>

    <?php // echo $form->field($model, 'tanggal_lahir') ?>

    <?php // echo $form->field($model, 'jk') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'kode_pos') ?>

    <?php // echo $form->field($model, 'no_telp') ?>

    <?php // echo $form->field($model, 'pendidikan') ?>

    <?php // echo $form->field($model, 'pekerjaan') ?>

    <?php // echo $form->field($model, 'suku') ?>

    <?php // echo $form->field($model, 'agama') ?>

    <?php // echo $form->field($model, 'pj_nama') ?>

    <?php // echo $form->field($model, 'gol_darah') ?>

    <?php // echo $form->field($model, 'berat') ?>

    <?php // echo $form->field($model, 'tinggi') ?>

    <?php // echo $form->field($model, 'pj_hubungan') ?>

    <?php // echo $form->field($model, 'pj_alamat') ?>

    <?php // echo $form->field($model, 'pj_telpon') ?>

    <?php // echo $form->field($model, 'nama_ayah') ?>

    <?php // echo $form->field($model, 'nama_ibu') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'user_input') ?>

    <?php // echo $form->field($model, 'user_modified') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
