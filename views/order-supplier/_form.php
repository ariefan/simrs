<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\InvSupplier;
use app\models\RefKodeRekening;
use app\models\InvItemMaster;
use yii\web\View;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\OrderSupplier */
/* @var $form yii\widgets\ActiveForm */

$data_barang = ArrayHelper::map(InvItemMaster::findBySql("SELECT 
                                              CONCAT(item_cd,
                                              '|',
                                              `unit_cd`,
                                              '|',
                                              `item_price_buy`,
                                              '|',
                                              `unit_cd`,
                                              '|',
                                              1) AS `item_cd`,
                                              CONCAT(`item_cd`,' ',`item_nm`,' ',`unit_cd`) as item_nm FROM `inv_item_master` ORDER BY item_nm")
                              ->asArray()->all(), 'item_cd', 'item_nm');
$select_barang = '<select class="form-control barang_item" name="barang[]">';
$select_barang .= "<option>--Pilih Obat--</option>";
$x=0;
foreach ($data_barang as $key => $value) {
    $value = preg_replace('/[^\w]+/', ' ', $value);
    $select_barang .= "<option value=\"$key\">$value</option>";
    if ($x==0)
        $t = explode('|', $key);
        $default_harga = $t[2];
        $default_satuan = $t[1];
    $x++;
}
$select_barang .= "</select>";

?>
<?php if(Yii::$app->session->getFlash('error')): ?>
<div class="alert alert-danger" role="alert">
  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
    <?= Yii::$app->session->getFlash('error'); ?>
</div>
<?php endif; ?>
<div class="order-supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_kode')->textInput(['maxlength' => true,]) ?>

    <?= $form->field($model, 'order_tanggal')->widget(
        DatePicker::className(), [
             'options' => [
                'placeholder' => "Format yyyy-mm-dd",
             ],
             'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
    ]); ?>

    <?= $form->field($model, 'supplier_cd')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(InvSupplier::find()->all(), 'supplier_cd', 'supplier_nm'),
        'options' => ['placeholder' => 'Pilih Supplier'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'kode_rekening')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(RefKodeRekening::findBySql("SELECT kode,CONCAT(kode,' - ',nama) AS nama FROM ref_kode_rekening")->all(), 'kode', 'nama'),
        'options' => ['placeholder' => 'Pilih Kode Rekening'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <div class="form-group">
        <button type="button" id="tambah_item" class="btn btn-primary">Tambah Item</button>
    </div>

    <div class="form-group">
        <table class="table">
            <thead>
                <tr>
                    <th>Aksi</th>
                    <th>Item</th>
                    <th>Jumlah Order</th>
                    <th>Satuan</th>
                    <th>Harga Item</th>
                    <th>Total Harga</th>                
                </tr>
            </thead>
            <tbody id="item_detail">
                <?php 
                if(isset($item_exist)){

                    foreach ($item_exist as $key => $value) {
                        $select_barang_exist = '<select class="form-control barang_item" name="barang[]">';
                        foreach ($data_barang as $key_b => $value_b) {
                            $selected = "";
                            $barang_id = explode('|', $key_b)[0];
                            if($barang_id==$value['item_cd']) $selected = "selected='selected'";
                            $select_barang_exist .= "<option $selected value=\"$key_b\">$value_b</option>";
                        }
                        $select_barang_exist .= "</select>";

                        echo '
                        <tr>
                            <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                            <td>
                                '.$select_barang_exist.'                     
                            </td>
                            <td><input type="number" value="'.$value['jumlah_order_supplier'].'" class="form-control jumlah_barang" name="jumlah_barang[]"></td>
                            <td>'.$value['satuan_supplier'].'</td>
                            <td><input class="form-control HB" type="text" name="harga_supplier_baru[]" value="'.$value['harga_supplier'].'"/></td>
                            <td class="total_harga">'.$value['harga_supplier']*$value['jumlah_order_supplier'].'</td>
                        </tr>
                        ';
                    }

                } else { ?>
                <tr>
                    <td><button type="button" class="btn btn-danger delete-item">x</button></td>
                    <td>
                        <?=
                            $select_barang
                        ?>                        
                    </td>
                    <td><input type="number" value="1" class="form-control jumlah_barang" name="jumlah_barang[]"></td>
                    <td><?= $default_satuan ?></td>
                    <td><input type="text" name="harga_supplier_baru[]" class="form-control HB" value="<?= $default_harga ?>"></td>
                    <td class="total_harga"><?= $default_harga ?></td>
                </tr>
                <?php
                } 
                ?>

                
            </tbody>
        </table>
    </div>

    <?= $form->field($model, 'total_harga')->textInput() ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$this->registerJs("
    jQuery(document).ready(function() {
        var f = true;
        inisilisasiDetail()

        $('#tambah_item').click(function(){
            f = false;
            var data = $('.barang_item').val().split('|')
            var harga_beli = data[2]
            var item_detail = '<tr>';
            item_detail += '<td><button type=\"button\" class=\"btn btn-danger delete-item\">x</button></td>';
            item_detail += '<td>$select_barang</td>';
            item_detail += '<td><input type=\"number\"  value=\"1\" class=\"form-control jumlah_barang\" name=\"jumlah_barang[]\"></td><td>".$default_satuan."</td><td><input class=\"form-control HB\" type=\"text\" name=\"harga_supplier_baru[]\" value=\"".$default_harga."\"></td><td class=\"total_harga\">".$default_harga."</td></tr>';
            
            $('#item_detail').append(item_detail);
            inisilisasiDetail()
        })

        function inisilisasiDetail(){
            $('.barang_item').select2({width:'100%'});

            $('.delete-item').click(function(){
                $(this).parent().parent().remove()
                hitungTotal()
            })
            
            $('.barang_item').change(function(){
                var data = $(this).val().split('|')
                var barang_id = data[0]
                var satuan_supplier = data[1]
                var harga_beli = data[2]
                $(this).parent().next().next().html(satuan_supplier)
                $(this).parent().next().next().next().find('.HB').val(harga_beli)
                
                var currJumlah = $(this).parent().next().find('.jumlah_barang').val()
                var currHarga = $(this).parent().next().next().next().find('.HB').val()
                $(this).parent().next().next().next().next().html(currJumlah * currHarga)
                hitungTotal()
            })            

            $('.jumlah_barang').change(function(){
                var currJumlah = $(this).parent().find('.jumlah_barang').val()
                var currHarga = $(this).parent().next().next().find('.HB').val()
                $(this).parent().next().next().next().html(currJumlah * currHarga)
                hitungTotal()
            })

            $('.HB').keyup(function(){
                var currHarga = $(this).parent().find('.HB').val()
                var currJumlah = $(this).parent().prev().prev().find('.jumlah_barang').val()
                // var currJumlah = $(this).parent().next().next().find('.HB').val()
                $(this).parent().next().html(currHarga * currJumlah)
                hitungTotal()
            })

            // $('.barang_item').trigger('change')
            // $('.jumlah_barang').trigger('keyup')
            hitungTotal()
        }

        function hitungTotal(){
            total = 0
            $('.total_harga').each(function(){
                total += parseFloat($(this).html().replace(',','').replace('.',''))
            })
            $('#ordersupplier-total_harga').val(total)
        }
    })

");