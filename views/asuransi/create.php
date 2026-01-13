<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\asuransi */

$this->title = 'Tambah Asuransi';
$this->params['breadcrumbs'][] = ['label' => 'Asuransi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asuransi-create">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
