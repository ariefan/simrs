<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tindakan */

$this->title = 'Tambah Tindakan: ' . $model->tindakan_cd;
$this->params['breadcrumbs'][] = ['label' => 'Tindakan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tindakan_cd, 'url' => ['view', 'id' => $model->tindakan_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tindakan-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
