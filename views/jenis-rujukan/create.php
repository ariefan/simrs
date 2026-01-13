<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JenisRujukan */

$this->title = 'Create Jenis Rujukan';
$this->params['breadcrumbs'][] = ['label' => 'Jenis Rujukans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-rujukan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
