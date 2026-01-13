<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InvPosItem */

$this->title = 'Create Inv Pos Item';
$this->params['breadcrumbs'][] = ['label' => 'Inv Pos Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-pos-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
