<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TarifTp */

$this->title = 'Tambah Tarif Tp';
$this->params['breadcrumbs'][] = ['label' => 'Tarif Tps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tarif-tp-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
