<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InvItemMaster */

$this->title = 'Update Item: ' . $model->item_cd;
$this->params['breadcrumbs'][] = ['label' => 'Inv Item Master', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->item_cd, 'url' => ['view', 'id' => $model->item_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inv-item-master-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
