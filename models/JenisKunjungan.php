<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_kunjungan".
 *
 * @property integer $jns_kunjungan_id
 * @property string $jns_kunjungan_nama
 */
class JenisKunjungan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_kunjungan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jns_kunjungan_id'], 'required'],
            [['jns_kunjungan_id'], 'integer'],
            [['jns_kunjungan_nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jns_kunjungan_id' => 'Jns Kunjungan ID',
            'jns_kunjungan_nama' => 'Jns Kunjungan Nama',
        ];
    }
}
