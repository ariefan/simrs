<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvItemType */

$this->title = 'Tambah Tipe Item';
$this->params['breadcrumbs'][] = ['label' => 'Tipe Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
