<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifKelas */

$this->title = 'Tambah Tarif Kelas';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-kelas-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
