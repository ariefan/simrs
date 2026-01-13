<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UnitMedis */

$this->title = 'Menambah Data Unit Medis';
$this->params['breadcrumbs'][] = ['label' => 'Unit Medis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-medis-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
