<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping37 */

$this->title = 'Update Rl Grouping37: ' . $model->rl_ref_37_no;
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping37s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rl_ref_37_no, 'url' => ['view', 'rl_ref_37_no' => $model->rl_ref_37_no, 'medicalunit_cd' => $model->medicalunit_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rl-grouping37-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
