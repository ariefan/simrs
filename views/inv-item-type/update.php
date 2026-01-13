<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemType */

$this->title = 'Ubah Tipe Item: ' . $model->type_cd;
$this->params['breadcrumbs'][] = ['label' => 'Tipe Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type_cd, 'url' => ['view', 'id' => $model->type_cd]];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="inv-item-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
