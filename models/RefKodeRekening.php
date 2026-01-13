<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_kode_rekening".
 *
 * @property string $kode
 * @property string $nama
 */
class RefKodeRekening extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_kode_rekening';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kode'], 'required'],
            [['kode', 'nama'], 'string', 'max' => 255],
            [['kode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kode' => 'Kode',
            'nama' => 'Nama',
        ];
    }
}
