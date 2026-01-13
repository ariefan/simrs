<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RefKokab */

$this->title = 'Create Ref Kokab';
$this->params['breadcrumbs'][] = ['label' => 'Ref Kokabs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-kokab-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
