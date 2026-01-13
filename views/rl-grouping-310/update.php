<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RlGrouping310 */

$this->title = 'Update Rl Grouping310: ' . $model->rl_ref_310_no;
$this->params['breadcrumbs'][] = ['label' => 'Rl Grouping310s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rl_ref_310_no, 'url' => ['view', 'rl_ref_310_no' => $model->rl_ref_310_no, 'tindakan_cd' => $model->tindakan_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rl-grouping310-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
