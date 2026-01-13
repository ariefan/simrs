<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bayar_rad".
 *
 * @property string $bayar_rad_id
 * @property string $no_invoice
 * @property string $rad_cd
 * @property string $nama_rad
 * @property string $harga
 *
 * @property UnitMedisItem $radCd
 * @property Bayar $noInvoice
 */
class BayarRad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bayar_rad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['harga','jumlah'], 'integer'],
            [['no_invoice'], 'string', 'max' => 20],
            [['rad_cd'], 'string', 'max' => 11],
            [['nama_rad'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bayar_rad_id' => 'Bayar Rad ID',
            'no_invoice' => 'No Invoice',
            'rad_cd' => 'Rad Cd',
            'nama_rad' => 'Nama Rad',
            'harga' => 'Harga',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRadCd()
    {
        return $this->hasOne(UnitMedisItem::className(), ['medicalunit_cd' => 'rad_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoInvoice()
    {
        return $this->hasOne(Bayar::className(), ['no_invoice' => 'no_invoice']);
    }
}
