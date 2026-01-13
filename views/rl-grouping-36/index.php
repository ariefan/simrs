<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RlGrouping36Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grouping RL 3.6';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if(Yii::$app->session->getFlash('error')): ?>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
    <?= Yii::$app->session->getFlash('error'); ?>
</div>
<?php endif; ?>
<?php if(Yii::$app->session->getFlash('success')): ?>
<div class="alert alert-success" role="alert">
  <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
    <?= Yii::$app->session->getFlash('success'); ?>
</div>
<?php endif; ?>
<div class="rl-grouping36-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>Spesialisasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data as $val): ?>
            <tr>
                <td><?= $val['spesialisasi'] ?></td>
                <td><?= Html::a('<span class="btn btn-default fa fa-pencil"></span>', Url::to(['rl-grouping-36/update','id'=>$val['no']]), [
                            'title' => Yii::t('yii', 'GROUPING'),
                            'data-pjax' => '0',
                        ]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
