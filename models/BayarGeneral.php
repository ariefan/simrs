<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bayar_general".
 *
 * @property integer $bayar_general_id
 * @property string $no_invoice
 * @property string $tarif_general_id
 * @property string $nama_tarif
 * @property string $tarif
 * @property integer $jumlah
 * @property string $total
 */
class BayarGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bayar_general';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tarif', 'jumlah', 'total'], 'integer'],
            [['no_invoice'], 'string', 'max' => 20],
            [['tarif_general_id'], 'string', 'max' => 10],
            [['nama_tarif'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bayar_general_id' => 'Bayar General ID',
            'no_invoice' => 'No Invoice',
            'tarif_general_id' => 'Tarif General ID',
            'nama_tarif' => 'Nama Tarif',
            'tarif' => 'Tarif',
            'jumlah' => 'Jumlah',
            'total' => 'Total',
        ];
    }
}
