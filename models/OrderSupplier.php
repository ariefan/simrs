<?php

namespace app\models;

use app\models\Supplier;
use Yii;



/**
 * This is the model class for table "order_supplier".
 *
 * @property integer $order_id
 * @property string $order_kode
 * @property string $order_tanggal
 * @property integer $supplier_cd
 * @property integer $cabang_id
 * @property string $status
 * @property string $catatan
 * @property string $catatan_receive
 * @property string $total_harga
 * @property integer $user_id
 * @property string $created
 * @property string $modified
 *
 * @property Supplier $supplier
 * @property Cabang $cabang
 * @property OrderSupplierDetail[] $orderSupplierDetails
 * @property Barang[] $barangs
 */
class OrderSupplier extends \yii\db\ActiveRecord
{
    public $supplier_receive = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_order_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_kode', 'order_tanggal', 'status', 'total_harga', 'user_id','supplier'], 'required'],
            [['order_kode', 'order_tanggal', 'status', 'total_harga', 'user_id'], 'required', 'on'=>'terima'],
            [['order_tanggal', 'created', 'modified','supplier_cd','jatuh_tempo'], 'safe'],
            [['total_harga', 'user_id'], 'integer'],
            ['supplier_receive', 'each', 'rule' => ['integer']],
            [['status'], 'string'],
            [['order_kode','no_faktur'], 'string', 'max' => 255],
            [['catatan', 'catatan_receive'], 'string', 'max' => 500]  
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'order_kode' => 'Order Kode',
            'order_tanggal' => 'Order Tanggal',
            'supplier_cd' => 'Supplier',
            'cabang_id' => 'Laboratorium ID',
            'status' => 'Status',
            'no_faktur' => 'No Faktur',
            'jatuh_tempo' => 'Jatuh Tempo',
            'catatan' => 'Catatan',
            'catatan_receive' => 'Catatan Receive',
            'total_harga' => 'Total Harga',
            'user_id' => 'User ID',
            'created' => 'Created',
            'modified' => 'Modified',
          
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(InvSupplier::className(), ['supplier_cd' => 'supplier_cd']);
    }

    

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderSupplierDetails()
    {
        return $this->hasMany(OrderSupplierDetail::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarangs()
    {
        return $this->hasMany(InvItemMaster::className(), ['item_cd' => 'item_cd'])->viaTable('inv_order_supplier_detail', ['order_id' => 'order_id']);
    }

    public static function getDataReport($stat = 'received', $PO = 'PO_13', $cat = '1')
    {
        $c = '';
        $role = (string)Yii::$app->user->identity->role;
        
        $rows =[];
        $order = OrderSupplier::find()->where('status = :st and order_kode = :ok '.$c,
            [':st'=>$stat, ':ok'=>$PO,])->all();

        $no = 0;

        foreach ($order as $key => $value) 
        {
            $user_id = $value->user_id;
            $orderDetail = $value->orderSupplierDetails;
            foreach ($orderDetail as $key2 => $value2) 
                if ($value2->barang->kategori_item_cd==$cat)
                {
                    $row = [];
                    $row[0] = ++$no;
                    $row[1] = $value2->barang->barang_nama; //nama barang
                    $row[2] = $value2->barang->spesifikasi; // spesifikasi
                    $row[3] = $stat == 'received' ? $value2->jumlah_receive_supplier : $value2->jumlah_order_supplier; //jumlah
                    $row[4] = $value2->harga_supplier; //harga
                    $row[5] = $value2->jumlah_order_supplier * $value2->harga_supplier; //harga total
                    $row[6] = User::findOne($value->user_id)->cabang->cabang_nama; //lab pengusul
                    $row[7] = $value->catatan;//catatan 
                    
                    if ($stat=='approved')
                        $row[8] = ($value2->supplier_cd==0)? "":Supplier::findOne($value2->supplier_cd)->supplier_nama; //supplier pemenang

                    $rows[] = $row;
                }
        }

        return $rows;
    }
}
