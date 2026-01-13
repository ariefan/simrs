<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping38 */

$this->title = 'Update Rl Grouping38: ' . $model->rl_ref_38_no;
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping38s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rl_ref_38_no, 'url' => ['view', 'rl_ref_38_no' => $model->rl_ref_38_no, 'medicalunit_cd' => $model->medicalunit_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rl-grouping38-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
