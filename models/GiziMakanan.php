<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gizi_makanan".
 *
 * @property integer $id
 * @property string $kode_diet
 * @property string $bahan_makanan
 * @property string $kandungan
 * @property double $jumlah_gizi
 * @property string $satuan
 * @property string $list_kandungan
 * @property string $deskripsi
 *
 * @property GiziDiet $kodeDiet
 */
class GiziMakanan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gizi_makanan';
    }
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jumlah_gizi'], 'number'],
            [['list_kandungan', 'deskripsi'], 'string'],
            [['kode_diet'], 'string', 'max' => 10],
            [['bahan_makanan', 'kandungan'], 'string', 'max' => 255],
            [['satuan'], 'string', 'max' => 50],
            [['kode_diet'], 'exist', 'skipOnError' => true, 'targetClass' => GiziDiet::className(), 'targetAttribute' => ['kode_diet' => 'kode']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode_diet' => 'Kode Diet',
            'bahan_makanan' => 'Bahan Makanan',
            'kandungan' => 'Kandungan',
            'jumlah_gizi' => 'Jumlah Gizi',
            'satuan' => 'Satuan',
            'list_kandungan' => 'List Kandungan',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodeDiet()
    {
        return $this->hasOne(GiziDiet::className(), ['kode' => 'kode_diet']);
    }
}
