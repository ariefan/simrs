<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\InvItemMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Warning Stok & Kadaluarsa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inv-item-master-index">

    <h1>Warning Stok</h1>

    <table class="table table-bordered">
        <tr>
            <th>Kode Item</th>
            <th>Item</th>
            <th>Jumlah Stok / Minimal</th>
        </tr>
    <?php foreach($stock_warnings as $warning){ ?>
        <tr>
            <td><?= $warning['item_cd'] ?></td>
            <td><?= $warning['item_nm'] ?></td>
            <td <?= $warning['stock_warning'] == 1 ? 'class="danger"' : '' ?>><?= $warning['trx_qty'] ?> / <?= $warning['minimum_stock'] ?></td>
        </tr>
    <?php } ?>
    </table>

    <h1>Warning Kadaluarsa</h1>

    <table class="table table-bordered">
        <tr>
            <th>Kode Item</th>
            <th>Item</th>
            <th>Tanggal Kadaluarsa</th>
            <th>Kadaluarsa (hari)</th>
        </tr>
    <?php foreach($expired_warnings as $warning){ ?>
        <tr>
            <td><?= $warning['item_cd'] ?></td>
            <td><?= $warning['item_nm'] ?></td>
            <td><?= $warning['expire_date'] ?></td>
            <td <?= $warning['expired_warning'] == 1 ? 'class="danger"' : '' ?>><?= $warning['days_left'] ?> hari lagi</td>
        </tr>
    <?php } ?>
    </table>
</div>
