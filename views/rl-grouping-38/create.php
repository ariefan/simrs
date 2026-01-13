<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping38 */

$this->title = 'Create Rl Grouping38';
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping38s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rl-grouping38-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
