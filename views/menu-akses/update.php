<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MenuAkses */

$this->title = 'Update Menu Akses: ' . $model->menu_id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Akses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->menu_id, 'url' => ['view', 'menu_id' => $model->menu_id, 'role' => $model->role]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-akses-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
