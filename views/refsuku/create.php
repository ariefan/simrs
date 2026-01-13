<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Refsuku */

$this->title = 'Create Refsuku';
$this->params['breadcrumbs'][] = ['label' => 'Refsukus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refsuku-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
