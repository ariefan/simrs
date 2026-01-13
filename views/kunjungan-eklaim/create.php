<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\KunjunganEklaim */

$this->title = 'Create Kunjungan Eklaim';
$this->params['breadcrumbs'][] = ['label' => 'Kunjungan Eklaims', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kunjungan-eklaim-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
