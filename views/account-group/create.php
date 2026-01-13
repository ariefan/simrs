<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccountGroup */

$this->title = 'Create Account Group';
$this->params['breadcrumbs'][] = ['label' => 'Account Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-group-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
