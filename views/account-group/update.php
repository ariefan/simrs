<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccountGroup */

$this->title = 'Update Account Group: ' . $model->accgroup_cd;
$this->params['breadcrumbs'][] = ['label' => 'Account Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->accgroup_cd, 'url' => ['view', 'id' => $model->accgroup_cd]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="account-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
