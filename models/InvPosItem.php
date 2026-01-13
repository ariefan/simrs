<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_pos_item".
 *
 * @property string $pos_cd
 * @property string $item_cd
 * @property string $quantity
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property InvItemMaster $itemCd
 * @property InvPosInventory $posCd
 */
class InvPosItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'inv_pos_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_cd', 'item_cd'], 'required'],
            [['quantity'], 'number'],
            [['modi_datetime'], 'safe'],
            [['pos_cd', 'item_cd', 'modi_id'], 'string', 'max' => 20],
            [['item_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvItemMaster::className(), 'targetAttribute' => ['item_cd' => 'item_cd']],
            [['pos_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvPosInventory::className(), 'targetAttribute' => ['pos_cd' => 'pos_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pos_cd' => 'POS',
            'item_cd' => 'Barang',
            'quantity' => 'jumlah',
            'modi_id' => 'Perubahan Oleh',
            'modi_datetime' => 'Terakhir Diubah',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(InvItemMaster::className(), ['item_cd' => 'item_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasOne(InvPosInventory::className(), ['pos_cd' => 'pos_cd']);
    }
}
