<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMove */

$this->title = 'Update Barang Masuk: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inv Item Moves', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inv-item-move-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'batch' => $batch,
    ]) ?>

</div>
