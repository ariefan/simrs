<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Kelas */

$this->title = 'Update Kelas: ' . $model->kelas_cd;
$this->params['breadcrumbs'][] = ['label' => 'Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kelas_cd, 'url' => ['view', 'id' => $model->kelas_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kelas-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
