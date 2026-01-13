<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifParamedis */

$this->title = 'Tambah Tarif Paramedis';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Paramedis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-paramedis-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
