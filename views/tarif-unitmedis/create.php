<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifUnitmedis */

$this->title = 'Tambah Tarif Unitmedis';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Unitmedis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-unitmedis-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
