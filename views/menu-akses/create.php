<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MenuAkses */

$this->title = 'Create Menu Akses';
$this->params['breadcrumbs'][] = ['label' => 'Menu Akses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-akses-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
