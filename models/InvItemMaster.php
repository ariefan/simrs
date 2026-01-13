<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_item_master".
 *
 * @property string $item_cd
 * @property string $type_cd
 * @property string $unit_cd
 * @property string $item_nm
 * @property string $barcode
 * @property string $currency_cd
 * @property string $item_price_buy
 * @property string $item_price
 * @property string $vat_tp
 * @property string $ppn
 * @property string $reorder_point
 * @property string $minimum_stock
 * @property string $maximum_stock
 * @property string $generic_st
 * @property string $active_st
 * @property string $inventory_st
 * @property string $tariftp_cd
 * @property string $last_user
 * @property string $last_update
 *
 * @property InvBatchItem[] $invBatchItems
 * @property InvUnit $unitCd
 * @property InvItemType $typeCd
 * @property InvItemMove[] $invItemMoves
 * @property InvPosItem[] $invPosItems
 * @property InvPosInventory[] $posCds
 * @property TarifInventori[] $tarifInventoris
 */
class InvItemMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_item_master';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_cd', 'type_cd', 'unit_cd'], 'required'],
            [['item_price_buy', 'item_price', 'ppn', 'reorder_point', 'minimum_stock', 'maximum_stock'], 'number'],
            [['last_update'], 'safe'],
            [['item_cd', 'type_cd', 'unit_cd', 'currency_cd', 'vat_tp', 'tariftp_cd', 'last_user'], 'string', 'max' => 20],
            [['item_nm'], 'string', 'max' => 100],
            [['barcode'], 'string', 'max' => 50],
            [['generic_st', 'active_st', 'inventory_st'], 'string', 'max' => 1],
            [['unit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvUnit::className(), 'targetAttribute' => ['unit_cd' => 'unit_cd']],
            [['type_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvItemType::className(), 'targetAttribute' => ['type_cd' => 'type_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_cd' => 'Kode Item',
            'type_cd' => 'Tipe Item',
            'unit_cd' => 'Unit',
            'item_nm' => 'Nama Item',
            'barcode' => 'Barcode',
            'currency_cd' => 'Mata Uang',
            'item_price_buy' => 'Harga Beli Item',
            'item_price' => 'Harga Item',
            'vat_tp' => 'Vat Tp',
            'ppn' => 'PPn',
            'reorder_point' => 'Reorder Point',
            'minimum_stock' => 'Stok Minimum',
            'maximum_stock' => 'Stok Maksimum',
            'generic_st' => 'Generic St',
            'active_st' => 'Active St',
            'inventory_st' => 'Inventory St',
            'tariftp_cd' => 'Tariftp Cd',
            'last_user' => 'User Terakhir',
            'last_update' => 'Update Terakhir',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvBatchItems()
    {
        return $this->hasMany(InvBatchItem::className(), ['item_cd' => 'item_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnitCd()
    {
        return $this->hasOne(InvUnit::className(), ['unit_cd' => 'unit_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeCd()
    {
        return $this->hasOne(InvItemType::className(), ['type_cd' => 'type_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvItemMoves()
    {
        return $this->hasMany(InvItemMove::className(), ['item_cd' => 'item_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvPosItems()
    {
        return $this->hasMany(InvPosItem::className(), ['item_cd' => 'item_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosCds()
    {
        return $this->hasMany(InvPosInventory::className(), ['pos_cd' => 'pos_cd'])->viaTable('inv_pos_item', ['item_cd' => 'item_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifInventoris()
    {
        return $this->hasMany(TarifInventori::className(), ['item_cd' => 'item_cd']);
    }
}