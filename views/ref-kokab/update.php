<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RefKokab */

$this->title = 'Update Ref Kokab: ' . $model->kota_id;
$this->params['breadcrumbs'][] = ['label' => 'Ref Kokabs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kota_id, 'url' => ['view', 'id' => $model->kota_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ref-kokab-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
