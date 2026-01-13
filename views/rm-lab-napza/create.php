<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RmLabNapza */

$this->title = 'Create Rm Lab Napza';
$this->params['breadcrumbs'][] = ['label' => 'Rm Lab Napzas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-lab-napza-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
