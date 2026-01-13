<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_supplier_detail".
 *
 * @property integer $order_id
 * @property integer $barang_id
 * @property integer $supplier_id
 * @property string $satuan_supplier
 * @property integer $jumlah_order_supplier
 * @property integer $jumlah_receive_supplier
 * @property string $kondisi_receive
 * @property string $satuan_stok
 * @property integer $jumlah_order_stok
 * @property integer $jumlah_receive_stok
 * @property integer $harga_supplier
 *
 * @property Barang $barang
 * @property OrderSupplier $order
 * @property ReturDetail $returDetail
 */
class OrderSupplierDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_order_supplier_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'item_cd'], 'required'],
            [['order_id', 'jumlah_order_supplier', 'jumlah_receive_supplier', 'jumlah_order_stok', 'jumlah_receive_stok', 'harga_supplier'], 'integer'],
            [['satuan_supplier', 'satuan_stok', 'item_cd'], 'string', 'max' => 50],
            [['kondisi_receive'], 'string', 'max' => 100],
            [['expired_date'],'safe'],
            [['item_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvItemMaster::className(), 'targetAttribute' => ['item_cd' => 'item_cd']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderSupplier::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'barang_id' => 'Barang ID',
            'satuan_supplier' => 'Satuan Supplier',
            'jumlah_order_supplier' => 'Jumlah Order Supplier',
            'jumlah_receive_supplier' => 'Jumlah Receive Supplier',
            'kondisi_receive' => 'Kondisi Receive',
            'satuan_stok' => 'Satuan Stok',
            'jumlah_order_stok' => 'Jumlah Order Stok',
            'jumlah_receive_stok' => 'Jumlah Receive Stok',
            'harga_supplier' => 'Harga Supplier',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(InvItemMaster::className(), ['item_cd' => 'item_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderSupplier::className(), ['order_id' => 'order_id']);
    }

}
