<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping311 */

$this->title = 'Create Rl Grouping311';
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping311s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping311-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
