<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_periksa_lab".
 *
 * @property integer $periksa_id
 * @property string $periksa_nama
 * @property string $periksa_group
 *
 * @property RmLabNapzaDetail[] $rmLabNapzaDetails
 */
class JenisPeriksaLab extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_periksa_lab';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['periksa_nama', 'periksa_group'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'periksa_id' => 'Periksa ID',
            'periksa_nama' => 'Periksa Nama',
            'periksa_group' => 'Periksa Group',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRmLabNapzaDetails()
    {
        return $this->hasMany(RmLabNapzaDetail::className(), ['periksa_id' => 'periksa_id']);
    }
}
