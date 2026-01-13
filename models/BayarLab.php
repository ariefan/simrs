<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bayar_lab".
 *
 * @property string $bayar_lab_id
 * @property string $no_invoice
 * @property string $lab_cd
 * @property string $nama_lab
 * @property string $harga
 *
 * @property UnitMedisItem $labCd
 * @property Bayar $noInvoice
 */
class BayarLab extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bayar_lab';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['harga','jumlah'], 'integer'],
            [['no_invoice'], 'string', 'max' => 20],
            [['lab_cd'], 'string', 'max' => 11],
            [['nama_lab'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bayar_lab_id' => 'Bayar Lab ID',
            'no_invoice' => 'No Invoice',
            'lab_cd' => 'Lab Cd',
            'nama_lab' => 'Nama Lab',
            'harga' => 'Harga',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabCd()
    {
        return $this->hasOne(UnitMedisItem::className(), ['medicalunit_cd' => 'lab_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNoInvoice()
    {
        return $this->hasOne(Bayar::className(), ['no_invoice' => 'no_invoice']);
    }
}
