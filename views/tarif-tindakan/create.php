<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifTindakan */

$this->title = 'Tambah Tarif Tindakan';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tindakan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-tindakan-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
