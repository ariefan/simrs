<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Klinik */

$this->title = 'Create Klinik';
$this->params['breadcrumbs'][] = ['label' => 'Kliniks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="klinik-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
