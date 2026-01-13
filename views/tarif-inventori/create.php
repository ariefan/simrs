<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifInventori */

$this->title = 'Tambah Tarif Inventori';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Inventori', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-inventori-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
