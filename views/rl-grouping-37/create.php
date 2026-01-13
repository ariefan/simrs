<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping37 */

$this->title = 'Create Rl Grouping37';
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping37s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping37-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
