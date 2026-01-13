<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ruang */

$this->title = 'Ubah Data Ruang: ' . $model->ruang_cd;
$this->params['breadcrumbs'][] = ['label' => 'Ruangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ruang_cd, 'url' => ['view', 'id' => $model->ruang_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ruang-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
