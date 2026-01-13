<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Konfigurasi */
?>
<div class="konfigurasi-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'KONF_KODE',
            'KONF_NILAI',
            'KONF_KETERANGAN',
        ],
    ]) ?>

</div>
