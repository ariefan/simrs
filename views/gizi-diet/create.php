<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GiziDiet */

$this->title = 'Tambah Gizi Diet';
$this->params['breadcrumbs'][] = ['label' => 'Gizi Diets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gizi-diet-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
