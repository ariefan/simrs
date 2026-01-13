<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ruang".
 *
 * @property string $ruang_cd
 * @property string $kelas_cd
 * @property string $bangsal_cd
 * @property string $ruang_nm
 * @property string $status
 *
 * @property Kelas $kelasCd
 * @property Bangsal $bangsalCd
 */
class Ruang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ruang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruang_cd', 'kelas_cd'], 'required'],
            [['ruang_cd', 'kelas_cd', 'bangsal_cd'], 'string', 'max' => 20],
            [['ruang_nm'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 1],
            [['kelas_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Kelas::className(), 'targetAttribute' => ['kelas_cd' => 'kelas_cd']],
            [['bangsal_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Bangsal::className(), 'targetAttribute' => ['bangsal_cd' => 'bangsal_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ruang_cd' => 'Kode Tempat Tidur',
            'kelas_cd' => 'Kode Kelas',
            'bangsal_cd' => 'Kode Bangsal',
            'status' => 'Status',
            'ruang_nm' => 'Nama Tempat Tidur',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKelasCd()
    {
        return $this->hasOne(Kelas::className(), ['kelas_cd' => 'kelas_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBangsalCd()
    {
        return $this->hasOne(Bangsal::className(), ['bangsal_cd' => 'bangsal_cd']);
    }
}
