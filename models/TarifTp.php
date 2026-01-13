<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tarif_tp".
 *
 * @property string $tariftp_no
 * @property string $tariftp_nm
 * @property string $insurance_cd
 * @property string $kelas_cd
 * @property string $tarif_total
 * @property string $trx_tarif_seqno
 * @property string $modi_id
 * @property string $tarif_tp
 * @property string $modi_datetime
 *
 * @property TarifTpItem[] $tarifTpItems
 */
class TarifTp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tarif_tp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // [['tariftp_no'], 'required'],
            [['tariftp_no'], 'integer'],
            [['tariftp_nm', 'trx_tarif_seqno'], 'string'],
            [['tarif_total'], 'number'],
            [['modi_datetime'], 'safe'],
            [['insurance_cd', 'kelas_cd', 'modi_id', 'tarif_tp'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tariftp_no' => 'Tariftp No',
            'tariftp_nm' => 'Tariftp Nm',
            'insurance_cd' => 'Insurance Cd',
            'kelas_cd' => 'Kelas Cd',
            'tarif_total' => 'Tarif Total',
            'trx_tarif_seqno' => 'Trx Tarif Seqno',
            'modi_id' => 'Modi ID',
            'tarif_tp' => 'Tarif Tp',
            'modi_datetime' => 'Modi Datetime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifTpItems()
    {
        return $this->hasMany(TarifTpItem::className(), ['tariftp_no' => 'tariftp_no']);
    }
}
