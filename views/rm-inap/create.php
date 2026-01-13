<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RmInap */

$this->title = 'Create Rm Inap';
$this->params['breadcrumbs'][] = ['label' => 'Rm Inaps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-inap-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
