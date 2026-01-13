<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ref_cara_pulang".
 *
 * @property integer $pulang_id
 * @property string $pulang_nama
 *
 * @property Kunjungan[] $kunjungans
 */
class RefCaraPulang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_cara_pulang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pulang_nama'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pulang_id' => 'Pulang ID',
            'pulang_nama' => 'Pulang Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunjungans()
    {
        return $this->hasMany(Kunjungan::className(), ['jenis_keluar' => 'pulang_id']);
    }
}
