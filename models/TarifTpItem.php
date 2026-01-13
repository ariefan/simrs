<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_tp_item".
 *
 * @property string $tariftp_no
 * @property string $seq_no
 * @property string $item_nm
 * @property string $tarif_tp
 * @property integer $trx_tarif_seqno
 * @property string $tarif_item
 * @property string $quantity
 * @property string $modi_id
 * @property string $modi_datetime
 *
 * @property TarifTp $tariftpNo
 */
class TarifTpItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_tp_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['tariftp_no', 'seq_no'], 'required'],
            [['tariftp_no', 'seq_no', 'trx_tarif_seqno'], 'integer'],
            [['item_nm'], 'string'],
            [['tarif_item', 'quantity'], 'number'],
            [['modi_datetime'], 'safe'],
            [['tarif_tp', 'modi_id'], 'string', 'max' => 20],
            [['tariftp_no'], 'exist', 'skipOnError' => true, 'targetClass' => TarifTp::className(), 'targetAttribute' => ['tariftp_no' => 'tariftp_no']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tariftp_no' => 'Tariftp No',
            'seq_no' => 'Seq No',
            'item_nm' => 'Item Nm',
            'tarif_tp' => 'Tarif Tp',
            'trx_tarif_seqno' => 'Trx Tarif Seqno',
            'tarif_item' => 'Tarif Item',
            'quantity' => 'Quantity',
            'modi_id' => 'Modi ID',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariftpNo()
    {
        return $this->hasOne(TarifTp::className(), ['tariftp_no' => 'tariftp_no']);
    }
}
