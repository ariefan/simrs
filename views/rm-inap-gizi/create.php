<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RmInapGizi */

$this->title = 'Create Rm Inap Gizi';
$this->params['breadcrumbs'][] = ['label' => 'Rm Inap Gizis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rm-inap-gizi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
