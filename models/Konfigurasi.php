<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "konfigurasi".
 *
 * @property string $KONF_KODE
 * @property string $KONF_NILAI
 * @property string $KONF_KETERANGAN
 */
class Konfigurasi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'konfigurasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KONF_KODE'], 'required'],
            [['KONF_KODE'], 'string', 'max' => 20],
            [['KONF_NILAI'], 'string', 'max' => 1000],
            [['KONF_KETERANGAN'], 'string', 'max' => 1500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'KONF_KODE' => 'Konf  Kode',
            'KONF_NILAI' => 'Konf  Nilai',
            'KONF_KETERANGAN' => 'Konf  Keterangan',
        ];
    }

    public static function getValue($key){
        $d = Konfigurasi::findOne($key);
        return !$d? "":$d->KONF_NILAI;
    }
}
