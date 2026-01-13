<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JenisKunjungan */
?>
<div class="jenis-kunjungan-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'jns_kunjungan_id',
            'jns_kunjungan_nama',
        ],
    ]) ?>

</div>
