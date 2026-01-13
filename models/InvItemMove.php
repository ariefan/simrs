<?php

namespace app\models;

use Yii;
use app\models\Konfigurasi;
use app\models\InvBatchItem;
use app\models\InvPosItem;
use app\models\InvItemMaster;

/**
 * This is the model class for table "inv_item_move".
 *
 * @property string $id
 * @property string $pos_cd
 * @property string $pos_destination
 * @property string $item_cd
 * @property string $batch_no
 * @property string $trx_by
 * @property string $trx_datetime
 * @property string $trx_qty
 * @property string $old_stock
 * @property string $new_stock
 * @property string $purpose
 * @property string $vendor
 * @property string $move_tp
 * @property string $note
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property InvPosInventory $posCd
 * @property InvPosInventory $posDestination
 * @property InvItemMaster $itemCd
 */
class InvItemMove extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $multi_barang;

    public static function tableName()
    {
        return 'inv_item_move';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['batch_no'], 'integer'],
            [['trx_datetime', 'modi_datetime','multi_barang'], 'safe'],
            [['trx_qty', 'old_stock', 'new_stock'], 'number'],
            [['purpose', 'move_tp', 'note'], 'string'],
            [['pos_cd', 'pos_destination', 'item_cd', 'modi_id','note'], 'string'],
            [['trx_by', 'vendor'], 'string', 'max' => 100],
            [['pos_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvPosInventory::className(), 'targetAttribute' => ['pos_cd' => 'pos_cd']],
            [['pos_destination'], 'exist', 'skipOnError' => true, 'targetClass' => InvPosInventory::className(), 'targetAttribute' => ['pos_destination' => 'pos_cd']],
           [['item_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvItemMaster::className(), 'targetAttribute' => ['item_cd' => 'item_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pos_cd' => 'Pos Sumber',
            'pos_destination' => 'Pos Tujuan',
            'item_cd' => 'Item',
            'batch_no' => 'No Batch',
            'trx_by' => 'Trx By',
            'trx_datetime' => 'Transaksi',
            'trx_qty' => 'Jumlah',
            'old_stock' => 'Stock Lama',
            'new_stock' => 'Stock Baru',
            'purpose' => 'Kegunaan',
            'vendor' => 'Vendor',
            'move_tp' => 'Jenis Transaksi',
            'note' => 'Catatan',
            'modi_id' => 'Perubahan Oleh',
            'modi_datetime' => 'Terakhir Diubah',
            //untuk di form view
            'pos.pos_nm'=>'Pos Asal',
            'posDestination'=>'Pos Tujuan',
            'multi_barang' => 'Input Barang:'
           

        ];
    }

    public function tambahStok($item_cd,$qty,$order_id,$supplier,$buy_price,$expire_date){
        $pos_cd = 'WHMASTER';
        $margin = Konfigurasi::getValue('HARGA_OBAT_1')/100;
        $margin_2 = Konfigurasi::getValue('HARGA_OBAT_2')/100;
        $model_batch = new InvBatchItem();
        $model_batch->item_cd = $item_cd;
        $model_batch->pos_cd = $pos_cd;
        $model_batch->supplier = $supplier;
        $model_batch->trx_qty = $qty;
        $model_batch->buy_price = $buy_price;
        $model_batch->sell_price = $buy_price + $buy_price*$margin;        
        $model_batch->sell_price_2 = $model_batch->sell_price + $model_batch->sell_price*$margin_2;        
        $model_batch->expire_date = $expire_date;
        $model_batch->order_id = $order_id;
        $model_batch->modi_id = Yii::$app->user->identity->username;
        $model_batch->modi_datetime = date('Y-m-d h:i:s');
        $model_batch->save();

        $stock_old = 0;
        $stock_new = $qty;
        $model_pos = InvPosItem::findOne(['pos_cd' => $pos_cd, 'item_cd' => $item_cd]);
        if ($model_pos !== null) {
            $stock_old = $model_pos->quantity;
            $model_pos->quantity += $qty;
            $stock_new = $model_pos->quantity;
            $model_pos->modi_id = Yii::$app->user->identity->username;
            $model_pos->modi_datetime = date('Y-m-d h:i:s');
            $model_pos->save();
        } else {
            $model_pos = new InvPosItem();
            $model_pos->pos_cd = $pos_cd;
            $model_pos->item_cd = $item_cd;
            $model_pos->quantity = $qty;
            $model_pos->modi_id = Yii::$app->user->identity->username;
            $model_pos->modi_datetime = date('Y-m-d h:i:s');
            $model_pos->save();
        }

        $model_move = new InvItemMove(); 
        $model_move->pos_cd = $pos_cd;
        $model_move->pos_destination = $pos_cd;
        $model_move->item_cd = $item_cd;
        $model_move->batch_no = $model_batch->batch_no;
        $model_move->trx_by = Yii::$app->user->identity->username;
        $model_move->trx_datetime = date('Y-m-d h:i:s');
        $model_move->trx_qty = $qty; 
        $model_move->old_stock = $qty; 
        $model_move->new_stock = $qty; 
        $model_move->purpose = 'Order Supplier order-id #'.$order_id; 
        $model_move->vendor = $supplier; 
        $model_move->move_tp = 'In'; 
        $model_move->note = ''; 
        $model_move->modi_id = Yii::$app->user->identity->username;
        $model_move->modi_datetime = date('Y-m-d h:i:s');
        $model_move->save();
    }



    public function kurangiStok($pos_cd,$item_cd,$qty,$note=''){
        $batch_used = '';
        $item = explode('|', $item_cd);
        $item_cd = $item[0];
        $hasil = true;
        $model_pos = InvPosItem::findOne(['pos_cd' => $pos_cd, 'item_cd' => $item_cd]);
        # 1. AMBIL STOK DARI SEMUA KODE BATCH
        $data_batch = InvBatchItem::find()
            ->where(['item_cd'=>$item_cd])
            ->andWhere(['>','trx_qty',0])
            ->andWhere(['pos_cd'=>$pos_cd])
            ->orderBy(['expire_date'=>SORT_ASC])
            ->asArray()->all();

        // echo '<pre>';
        // print_r($data_batch);
        // exit;

        $stock_old = 0;
        $stock_new = 0;
        $stock_sisa = $qty;

        #2. KURANGI DI POS SUMMARY
        if ($model_pos !== null) {
            if($model_pos->quantity<$qty)
                return false;

            $model_pos->quantity -= $qty;
            $model_pos->modi_id = Yii::$app->user->identity->username;
            $model_pos->modi_datetime = date('Y-m-d h:i:s');
            $hasil = $hasil && $model_pos->save();
        } else {
            return false;
        }

        #3. KURANGI DI SETIAP BATCH DAN CATAT DI MOVE PER BATCH
        #   BATCH YANG 0 SAAT INI TETAP DISIMPAN UNTUK MENJAGA REFERENSI KE MOVE
        $stop_batch = false;
        foreach ($data_batch as $key => $value) {
            $model_batch = InvBatchItem::findOne($value['batch_no']);
            $stock_old = $model_batch->trx_qty;
            if($model_batch->trx_qty > $stock_sisa){
                $model_batch->trx_qty -= $stock_sisa;
                $stop_batch = true;
            } else {
                $stock_sisa -= $model_batch->trx_qty;
                $model_batch->trx_qty = 0;
            }
            $hasil = $hasil && $model_batch->save();

            $model_move = new InvItemMove(); 
            $model_move->pos_cd = $pos_cd;
            $model_move->pos_destination = $pos_cd;
            $model_move->item_cd = $item_cd;
            $model_move->batch_no = $model_batch->batch_no;
            $model_move->trx_by = Yii::$app->user->identity->username;
            $model_move->trx_datetime = date('Y-m-d h:i:s');
            $model_move->trx_qty = $stock_sisa; 
            $model_move->old_stock = $stock_old; 
            $model_move->new_stock = $model_batch->trx_qty; 
            $model_move->purpose = 'Stock Out batch-no #'.$model_batch->batch_no; 
            $model_move->vendor = 'internal'; 
            $model_move->move_tp = 'Out'; 
            $model_move->note = $note;
            $model_move->modi_id = Yii::$app->user->identity->username;
            $model_move->modi_datetime = date('Y-m-d h:i:s');
            $hasil = $hasil && $model_move->save();
            $batch_used = $model_batch->batch_no;
            if($stop_batch)
                break;
        }

        return $batch_used;
        
    }

    public function cancelKurangiStok($pos_cd,$item_cd,$qty,$batch_no,$notes=''){
        $model_pos = InvPosItem::findOne(['pos_cd' => $pos_cd, 'item_cd' => $item_cd]);
        $stock_old = $qty;
        $stock_new = $qty;
        if ($model_pos !== null) {
            $stock_old = $model_pos->quantity;
            $model_pos->quantity += $qty;
            $stock_new = $model_pos->quantity;
            $model_pos->modi_id = Yii::$app->user->identity->username;
            $model_pos->modi_datetime = date('Y-m-d h:i:s');
            $model_pos->save();
        } else {
            $model_pos = new InvPosItem();
            $model_pos->pos_cd = $pos_cd;
            $model_pos->item_cd = $item_cd;
            $model_pos->quantity = $qty;
            $model_pos->modi_id = Yii::$app->user->identity->username;
            $model_pos->modi_datetime = date('Y-m-d h:i:s');
            $model_pos->save();
        }

        $model_batch = InvBatchItem::findOne(['batch_no'=>$batch_no]);
        $model_batch->trx_qty = $model_batch->trx_qty + $qty;
        $model_batch->save();

        $model_move = new InvItemMove(); 
        $model_move->pos_cd = $pos_cd;
        $model_move->pos_destination = $pos_cd;
        $model_move->item_cd = $item_cd;
        $model_move->batch_no = $batch_no;
        $model_move->trx_by = Yii::$app->user->identity->username;
        $model_move->trx_datetime = date('Y-m-d h:i:s');
        $model_move->trx_qty = $qty; 
        $model_move->old_stock = $stock_old; 
        $model_move->new_stock = $stock_new; 
        $model_move->purpose = 'Cancel-Proses-Stok-Farmasi'; 
        $model_move->vendor = ''; 
        $model_move->move_tp = 'In'; 
        $model_move->note = $notes;
        $model_move->modi_id = Yii::$app->user->identity->username;
        $model_move->modi_datetime = date('Y-m-d h:i:s');
        $model_move->save();

        return true;
    }

    public function transferStok($pos_cd,$pos_destination,$item_cd,$qty){
        $item = explode('|', $item_cd);
        $item_cd = $item[0];
        $hasil = true;

        $stock_old = 0;
        $stock_new = 0;
        $stock_sisa= $qty;

        $model_pos = InvPosItem::findOne(['pos_cd' => $pos_cd, 'item_cd' => $item_cd]);

        # 0. AMBIL STOK DARI SEMUA KODE BATCH
        $data_batch = InvBatchItem::find()
            ->where(['item_cd'=>$item_cd])
            ->andWhere(['>','trx_qty',0])
            ->andWhere(['pos_cd'=>$pos_cd])
            ->orderBy(['expire_date'=>SORT_ASC])
            ->asArray()->all();

        $stop_batch = false;

        # 1. TAMBAH STOK DI BATCH
        foreach ($data_batch as $key => $value) {
            $model_batch = InvBatchItem::findOne($value['batch_no']);
            if($model_batch->trx_qty > $stock_sisa){
                $model_batch->trx_qty -= $stock_sisa;
                $stock_pindah= $stock_sisa;
                $stop_batch = true;
            } else {
                $stock_sisa -= $model_batch->trx_qty;
                $model_batch->trx_qty = 0;
                $stock_pindah = $model_batch->trx_qty;
            }

            $data_pindah = InvBatchItem::findOne(['pos_cd' => $pos_destination, 'batch_no' => $value['batch_no']]);

            if ($data_pindah !==null){
                $model_batch->item_cd = $item_cd;
                $model_batch->pos_cd = $pos_destination;
                $model_batch->supplier = $value['supplier'];
                $model_batch->trx_qty = $stock_pindah;
                $model_batch->buy_price = $value['buy_price'];
                $model_batch->sell_price =$value['sell_price'];     
                $model_batch->sell_price_2 = $value['sell_price_2'];        
                $model_batch->expire_date = $value['expire_date'];
            
                $model_batch->modi_id = Yii::$app->user->identity->username;
                $model_batch->modi_datetime = date('Y-m-d h:i:s');

                // $model_batch->save();
            }else{
                $model_batch = new InvBatchItem();
                $model_batch->item_cd = $item_cd;
                $model_batch->pos_cd = $pos_destination;
                $model_batch->supplier = $value['supplier'];
                $model_batch->trx_qty = $stock_pindah;
                $model_batch->buy_price = $value['buy_price'];
                $model_batch->sell_price =$value['sell_price'];     
                $model_batch->sell_price_2 = $value['sell_price_2'];        
                $model_batch->expire_date = $value['expire_date'];
            
                $model_batch->modi_id = Yii::$app->user->identity->username;
                $model_batch->modi_datetime = date('Y-m-d h:i:s');

                // $model_batch->save();
            }

            $hasil = $hasil && $model_batch->save();
            if($stop_batch)
                break;
        }

        #2. TAMBAH STOK DI SUMMARY
        $model_pos_destination = InvPosItem::findOne(['pos_cd' => $pos_destination, 'item_cd' => $item_cd]);
        if ($model_pos_destination !== null) {
            $model_pos_destination->quantity += $qty;
            $model_pos_destination->modi_id = Yii::$app->user->identity->username;
            $model_pos_destination->modi_datetime = date('Y-m-d h:i:s');
            $hasil = $hasil && $model_pos_destination->save();
        } else {
            $model_pos_destination = new InvPosItem();
            $model_pos_destination->pos_cd = $pos_destination;
            $model_pos_destination->item_cd = $item_cd;
            $model_pos_destination->quantity = $qty;
            $model_pos_destination->modi_id = Yii::$app->user->identity->username;
            $model_pos_destination->modi_datetime = date('Y-m-d h:i:s');
            $hasil = $hasil && $model_pos_destination->save();
        }

        #3. KURANGI STOK DI ASAL

        $this->kurangiStok($pos_cd,$item_cd,$qty,"Transfer Stok dari $pos_cd ke $pos_destination");

        #   4. CATAT DI MOVE
        #   SEMENTARA KITA HIRAUKAN BATCH CODE DI MOVE
        #   MUNGKIN AKAN JADI MASALAH NANTINYA KARENA 
        #   PENCATATAN EXPIRED DATE MELEKAT DI BATCH :P
        $model_move = new InvItemMove(); 
        $model_move->pos_cd = $pos_cd;
        $model_move->pos_destination = $pos_destination;
        $model_move->item_cd = $item_cd;
        $model_move->batch_no = '';
        $model_move->trx_by = Yii::$app->user->identity->username;
        $model_move->trx_datetime = date('Y-m-d h:i:s');
        $model_move->trx_qty = $qty; 
        $model_move->old_stock = $stock_old; 
        $model_move->new_stock = $stock_new; 
        $model_move->purpose = 'Transfer Stok Item #'.$item_cd; 
        $model_move->vendor = ''; 
        $model_move->move_tp = 'Transfer'; 
        $model_move->note = ''; 
        $model_move->modi_id = Yii::$app->user->identity->username;
        $model_move->modi_datetime = date('Y-m-d h:i:s');
        $hasil = $hasil && $model_move->save();

        return $hasil;


        # 4. buat batch
        $model_batch = new InvBatchItem();
        $model_batch->item_cd = $item_cd;
        $model_batch->pos_cd = $pos_cd;
        $model_batch->trx_qty = $qty;            
        $model_batch->expire_date = $expire_date;
        $model_batch->modi_id = Yii::$app->user->identity->username;
        $model_batch->modi_datetime = date('Y-m-d h:i:s');
        $model_batch->save();

    }

    public function sesuaikanStok($pos_cd,$item_cd,$old_qty,$qty,$catatan){
        # 1. AMBIL STOK DARI SEMUA KODE BATCH
        $data_batch = InvBatchItem::find()
            ->where(['item_cd'=>$item_cd])
            ->andWhere(['>','trx_qty',0])
            ->orderBy(['expire_date'=>SORT_ASC])
            ->asArray()->all();

        $batch_no = '';
        foreach ($data_batch as $key => $value) {
            $batch_no = $value['batch_no'];
            $model_batch = InvBatchItem::findOne($value['batch_no']);
            $model_batch->trx_qty = $qty;
            $model_batch->save();
            break;
        }

        $model_move = new InvItemMove(); 
        $model_move->pos_cd = $pos_cd;
        $model_move->pos_destination = $pos_cd;
        $model_move->item_cd = $item_cd;
        $model_move->batch_no = $batch_no;
        $model_move->trx_by = Yii::$app->user->identity->username;
        $model_move->trx_datetime = date('Y-m-d h:i:s');
        $model_move->trx_qty = $qty; 
        $model_move->old_stock = $old_qty; 
        $model_move->new_stock = $qty; 
        $model_move->purpose = '';
        $model_move->vendor = ''; 
        $model_move->move_tp = 'Penyesuaian'; 
        $model_move->note = $catatan; 
        $model_move->modi_id = Yii::$app->user->identity->username;
        $model_move->modi_datetime = date('Y-m-d h:i:s');
        $model_move->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosCd()
    {
        return $this->hasOne(InvPosInventory::className(), ['pos_cd' => 'pos_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosDestination()
    {
        return $this->hasOne(InvPosInventory::className(), ['pos_cd' => 'pos_destination']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCd()
    {
        return $this->hasOne(InvItemMaster::className(), ['item_cd' => 'item_cd']);
    }
}
