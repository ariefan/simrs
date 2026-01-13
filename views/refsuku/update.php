<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Refsuku */

$this->title = 'Update Refsuku: ' . $model->suku_id;
$this->params['breadcrumbs'][] = ['label' => 'Refsukus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->suku_id, 'url' => ['view', 'id' => $model->suku_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="refsuku-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
