<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaim */

$this->title = $model->kunjungan_id;
$this->params['breadcrumbs'][] = ['label' => 'Kunjungan Eklaims', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunjungan-eklaim-view">
<div class="row">
    <div class="col-md-3">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'nomor_sep',
                'kelas_rawat',
                'adl_sub_acute',
                'adl_chronic',
                'icu_indikator',
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'icu_los',
                'ventilator_hour',
                'upgrade_class_ind',
                'upgrade_class_class',
                'upgrade_class_los',
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'add_payment_pct',
                'birth_weight',
                'discharge_status',
                'procedure:ntext',
                'tarif_rs',
            ],
        ]) ?>
    </div>
    <div class="col-md-3">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'tarif_poli_eks',
                'kode_tarif',
                'payor',
                'cob_cd',
            ],
        ]) ?>
    </div>

    
</div>

</div>
