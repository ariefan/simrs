<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_batch_item".
 *
 * @property string $batch_no
 * @property string $item_cd
 * @property string $trx_qty
 * @property string $batch_no_start
 * @property string $batch_no_end
 * @property string $expire_date
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property InvItemMaster $itemCd
 */
class InvBatchItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_batch_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_cd'], 'required'],
            [['trx_qty'], 'number'],
            [['expire_date', 'modi_datetime'], 'safe'],
            [['item_cd', 'batch_no_start', 'batch_no_end', 'modi_id'], 'string', 'max' => 20],
            [['item_cd'], 'exist', 'skipOnError' => true, 'targetClass' => InvItemMaster::className(), 'targetAttribute' => ['item_cd' => 'item_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'batch_no' => 'Batch No',
            'item_cd' => 'Item Cd',
            'trx_qty' => 'Trx Qty',
            'batch_no_start' => 'Batch No Start',
            'batch_no_end' => 'Batch No End',
            'expire_date' => 'Expire Date',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemCd()
    {
        return $this->hasOne(InvItemMaster::className(), ['item_cd' => 'item_cd']);
    }
}
