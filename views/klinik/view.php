<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Klinik */

$this->title = $model->klinik_id;
$this->params['breadcrumbs'][] = ['label' => 'Kliniks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="klinik-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Ubah', ['update', 'id' => $model->klinik_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Hapus', ['delete', 'id' => $model->klinik_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'klinik_id',
            'klinik_nama',
            'region_cd',
            'kode_pos',
            'fax',
            'email:email',
            'website',
            'alamat:ntext',
            'nomor_telp_1',
            'nomor_telp_2',
            'kepala_klinik',
            'maximum_row',
            'luas_tanah',
            'luas_bangunan',
            'izin_no',
            'izin_tgl',
            'izin_oleh',
            'izin_sifat',
            'izin_masa_berlaku',
        ],
    ]) ?>

</div>
