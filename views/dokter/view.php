<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Pasien;

$pasien = new Pasien();

$this->registerCssFile('@web/metronic/pages/css/profile.min.css',['depends'=>'app\assets\MetronicAsset']);

/* @var $this yii\web\View */
/* @var $model app\models\Dokter */

$this->title = 'Profil : '.$model->nama;
?>
<center><h3>Profil Saya</h3></center>
<br/>
<div class="row">
    <div class="col-md-3">
        <div class="profile-userpic">
            <?= empty($model->foto) ? Html::img('@web/img/DR-avatar.png',['class'=>'img-responsive']) : Html::img('@web/'.$model->foto,['class'=>'img-responsive']) ?>
        </div>
        <div class="profile-usertitle">
            <div class="profile-usertitle-name"> <?= $model->nama ?> </div>
            <div class="profile-usertitle-job"> <?= !empty($model->tanggal_lahir) ? $pasien->getAge($model->tanggal_lahir).' Tahun' : '-' ?>     </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR BUTTONS -->
        <div class="profile-userbuttons">
            <?= Html::a('Update Data', ['update', 'id' => $model->user_id], ['class' => 'btn btn-circle green btn-sm']) ?>
        </div>
    </div>
    <div class="col-md-9">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'nama',
                'jenis_kelamin',
                'tanggal_lahir',
                'spesialisasi.nama',
                'no_telp',
                'no_telp_2',
                'kota.kokab_nama',
                'email',
                'alumni',
                'pekerjaan',
                'alamat:ntext',
                'created',
            ],
        ]) ?>
    </div>
</div>
