<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvPosInventory */

$this->title = 'Tambah Pos Inventori';
$this->params['breadcrumbs'][] = ['label' => 'Pos Inventori', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-pos-inventory-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
