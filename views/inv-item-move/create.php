<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvItemMove */

$this->title = 'Tambah Barang Masuk';
$this->params['breadcrumbs'][] = ['label' => 'Barang Masuk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-move-create">

    <?= $this->render('_form', compact('batch','model')) ?>

</div>
