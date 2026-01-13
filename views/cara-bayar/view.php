<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CaraBayar */
?>
<div class="cara-bayar-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cara_id',
            'cara_nama',
            'cara_tipe',
            'harga_obat',
        ],
    ]) ?>

</div>
