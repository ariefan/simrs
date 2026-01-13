<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kelas".
 *
 * @property string $kelas_cd
 * @property string $kelas_nm
 *
 * @property Ruang[] $ruangs
 * @property TarifGeneral[] $tarifGenerals
 * @property TarifKelas[] $tarifKelas
 * @property TarifParamedis[] $tarifParamedis
 */
class Kelas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kelas_cd'], 'required'],
            [['kelas_cd'], 'string', 'max' => 20],
            [['kelas_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kelas_cd' => 'Kelas Cd',
            'kelas_nm' => 'Kelas Nm',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuangs()
    {
        return $this->hasMany(Ruang::className(), ['kelas_cd' => 'kelas_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifGenerals()
    {
        return $this->hasMany(TarifGeneral::className(), ['kelas_cd' => 'kelas_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifKelas()
    {
        return $this->hasMany(TarifKelas::className(), ['kelas_cd' => 'kelas_cd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarifParamedis()
    {
        return $this->hasMany(TarifParamedis::className(), ['kelas_cd' => 'kelas_cd']);
    }
}