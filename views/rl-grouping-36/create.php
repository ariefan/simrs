<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping36 */

$this->title = 'Create Rl Grouping36';
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping36s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping36-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
