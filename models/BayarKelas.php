<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bayar_kelas".
 *
 * @property string $bayar_kelas_id
 * @property string $no_invoice
 * @property string $tarif_kelas_id
 * @property string $nama_kelas
 * @property integer $jumlah
 * @property string $harga
 */
class BayarKelas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bayar_kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no_invoice', 'tarif_kelas_id', 'nama_kelas', 'jumlah', 'harga'], 'required'],
            [['jumlah'], 'integer'],
            [['harga'], 'number'],
            [['no_invoice'], 'string', 'max' => 20],
            [['tarif_kelas_id'], 'string', 'max' => 10],
            [['nama_kelas'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bayar_kelas_id' => 'Bayar Kelas ID',
            'no_invoice' => 'No Invoice',
            'tarif_kelas_id' => 'Tarif Kelas ID',
            'nama_kelas' => 'Nama Kelas',
            'jumlah' => 'Jumlah',
            'harga' => 'Harga',
        ];
    }
}
