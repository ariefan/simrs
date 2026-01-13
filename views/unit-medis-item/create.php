<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UnitMedisItem */

$this->title = 'Menambah Data Item Unit Medis';
$this->params['breadcrumbs'][] = ['label' => 'Unit Medis Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-medis-item-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
