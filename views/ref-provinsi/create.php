<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RefProvinsi */

$this->title = 'Create Ref Provinsi';
$this->params['breadcrumbs'][] = ['label' => 'Ref Provinsis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-provinsi-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
