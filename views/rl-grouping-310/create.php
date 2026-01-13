<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping310 */

$this->title = 'Create Rl Grouping310';
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping310s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping310-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
