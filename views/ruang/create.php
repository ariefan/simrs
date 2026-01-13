<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ruang */

$this->title = 'Menambah Data Ruang';
$this->params['breadcrumbs'][] = ['label' => 'Ruangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ruang-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
