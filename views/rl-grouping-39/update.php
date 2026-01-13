<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping39 */

$this->title = 'Update Rl Grouping39: ' . $model->rl_ref_39_no;
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping39s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rl_ref_39_no, 'url' => ['view', 'rl_ref_39_no' => $model->rl_ref_39_no, 'tindakan_cd' => $model->tindakan_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rl-grouping39-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
