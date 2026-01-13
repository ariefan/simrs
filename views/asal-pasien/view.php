<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AsalPasien */
?>
<div class="asal-pasien-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'asal_id',
            'asal_nama',
        ],
    ]) ?>

</div>
