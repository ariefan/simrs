<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\InvSupplier;


/* @var $this yii\web\View */
/* @var $model app\models\OrderSupplier */

$this->title = "Detail Order ";
$this->params['breadcrumbs'][] = ['label' => 'Order Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$role = (string)Yii::$app->user->identity->role;
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

<div class="order-supplier-view">

    <p id="button-row">
        <?php 
        if($model->status == 'ordered'){
            if ($role[0]=='1')
            {
                echo Html::a('Approve', ['approve', 'id' => $model->order_id], 
                        ['class' => 'btn btn-success',
                         'data' => [
                            'confirm' => 'Apakah anda yakin akan melakukan approve atas order ini?',
                            'method' => 'post',
                          ],
                        ])." "; 
                /*echo Html::a('Reject', ['disapprove', 'id' => $model->order_id], 
                        ['class' => 'btn btn-danger',
                        ])." "; */
            }
            echo Html::a('Update', ['update', 'id' => $model->order_id], ['class' => 'btn btn-primary'])." ";
            echo Html::a('Delete', ['delete', 'id' => $model->order_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Apakah anda yakin akan Menghapus data ini?',
                    'method' => 'post',
                ],
            ]);

        } elseif($model->status == 'approved'){
            if  ($role[0]=='1')
            echo Html::a('Batal Approve', ['cancel-approve', 'id' => $model->order_id], 
                ['class' => 'btn btn-default',
                 'data' => [
                    'confirm' => 'Apakah anda yakin akan melakukan pembatalan approve atas order ini?',
                    'method' => 'post',
                  ],
                ])." "; 

            echo Html::a('Terima', ['receive', 'id' => $model->order_id], 
                ['class' => 'btn btn-success']); 
            
        } elseif($model->status == 'received'){
            echo Html::a('Terima Kembali', ['order-supplier/receive', 'id' => $model->order_id],['class' => 'btn btn-success']); 
            // echo Html::a('Batal Terima', ['cancel-receive', 'id' => $model->order_id], 
            //     ['class' => 'btn btn-default','id'=>'diss',
            //      'data' => [
            //         'confirm' => 'Apakah anda yakin akan melakukan pembatalan penerimaan atas order ini?',
            //         'method' => 'post',
            //       ],
            //     ])." "; 
            // echo Html::a('Retur', ['retur/create', 'id' => $model->order_id],['class' => 'btn btn-primary']); 
        }
        elseif(($model->status == 'disapproved')&& ($role[0]=='1')){
            echo Html::a('Batal Reject', ['cancel-disapprove', 'id' => $model->order_id], 
                ['class' => 'btn btn-default','id'=>'diss',
                 'data' => [
                    'confirm' => 'Apakah anda yakin akan melakukan pembatalan disapproval atas order ini?',
                    'method' => 'post',
                  ],
                ])." "; 
        }

        ?>
    </p>
    <div id="canvasList_detail">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'order_id',
            'order_kode',
            'order_tanggal',
            'supplier.supplier_nm',
            // 'cabang.cabang_nama',
            'status',
            'no_faktur',
            'kode_rekening',
            'jatuh_tempo',
           // 'catatan',
            'catatan_receive',
            'total_harga:decimal',
            //'user_id',
          //  'created',
         //   'modified',
            
        ],
    ]) ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Jml Order</th>
                <th>Jml Terima</th>
                <th>Kondisi Terima</th>
                <th>Satuan</th>
                <th>Harga Item</th>
                <th>Total Harga</th>                
            </tr>
        </thead>
        <tbody id="item_detail">
        <?php 
        foreach ($item_exist as $key => $value) {
            echo '<tr>
                <td>'.$value['barang']['item_cd'].'</td>
                <td>'.$value['barang']['item_nm'].'</td>
                <td>'.$value['jumlah_order_supplier'].'</td>
                <td>'.$value['jumlah_receive_supplier'].'</td>
                <td>'.$value['kondisi_receive'].'</td>
                <td>'.$value['satuan_supplier'].'</td>
                <td>'.number_format($value['harga_supplier'],0,'','.').'</td>
                <td>'.number_format($value['harga_supplier']*$value['jumlah_order_supplier'],0,'','.').'</td>';
               
            echo '</tr>';
        } ?>
        </tbody>

    </table>

</div>
</div>
<div class="form-group" style="margin-bottom: 50px">
            <?= Html::button('cetak penerimaan',['class'=>'btn btn-primary pull-right','onclick'=>'printElem_print();']); ?>
        </div>


<script type="text/javascript">
    function printElem_print()
    {
         w = window.open();
        w.document.write("<?= $this->render('headerStruk') ?>");

        w.document.write(document.getElementById('canvasList_detail').innerHTML);
            
        w.document.write('<scr' + 'ipt type="text/javascript">' + 'window.onload = function() { window.print(); window.close(); };' + '</sc' + 'ript>');
        w.document.write('</body></html>');
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        return false;
     
        return false;
    }
</script>