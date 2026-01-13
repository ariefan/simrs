<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvItemMaster */

$this->title = 'Tambah Item';
$this->params['breadcrumbs'][] = ['label' => 'Inv Item Master', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-master-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
