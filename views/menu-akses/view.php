<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MenuAkses */

$this->title = $model->menu_id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Akses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-akses-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'menu_id' => $model->menu_id, 'role' => $model->role], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'menu_id' => $model->menu_id, 'role' => $model->role], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'menu_id',
            'role',
        ],
    ]) ?>

</div>
