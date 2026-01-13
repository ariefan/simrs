<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_tindakan_coding".
 *
 * @property string $id
 * @property string $rm_id
 * @property string $kode
 * @property string $long_desc
 * @property string $short_desc
 *
 * @property RekamMedis $rm
 * @property EklaimIcd9cm $kode0
 */
class RmTindakanCoding extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_tindakan_coding';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rm_id'], 'integer'],
            [['long_desc'], 'string'],
            [['kode', 'short_desc'], 'string', 'max' => 255],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedis::className(), 'targetAttribute' => ['rm_id' => 'rm_id']],
            [['kode'], 'exist', 'skipOnError' => true, 'targetClass' => EklaimIcd9cm::className(), 'targetAttribute' => ['kode' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rm_id' => 'Rm ID',
            'kode' => 'Kode',
            'long_desc' => 'Long Desc',
            'short_desc' => 'Short Desc',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRm()
    {
        return $this->hasOne(RekamMedis::className(), ['rm_id' => 'rm_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKode0()
    {
        return $this->hasOne(EklaimIcd9cm::className(), ['id' => 'kode']);
    }
}
