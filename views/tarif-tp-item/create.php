<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifTpItem */

$this->title = 'Create Tarif Tp Item';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tp Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-tp-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
