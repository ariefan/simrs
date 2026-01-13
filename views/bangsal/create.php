<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bangsal */

$this->title = 'Tambah Bangsal';
$this->params['breadcrumbs'][] = ['label' => 'Bangsals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bangsal-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
