<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_lab_napza_detail".
 *
 * @property string $napza_detail_id
 * @property integer $lab_napza_id
 * @property integer $periksa_id
 * @property string $hasil
 *
 * @property RmLabNapza $labNapza
 * @property JenisPeriksaLab $periksa
 */
class RmLabNapzaDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_lab_napza_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lab_napza_id', 'periksa_id'], 'integer'],
            [['hasil'], 'string', 'max' => 500],
            [['lab_napza_id'], 'exist', 'skipOnError' => true, 'targetClass' => RmLabNapza::className(), 'targetAttribute' => ['lab_napza_id' => 'lab_napza_id']],
            [['periksa_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPeriksaLab::className(), 'targetAttribute' => ['periksa_id' => 'periksa_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'napza_detail_id' => 'Napza Detail ID',
            'lab_napza_id' => 'Lab Napza ID',
            'periksa_id' => 'Periksa ID',
            'hasil' => 'Hasil',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLabNapza()
    {
        return $this->hasOne(RmLabNapza::className(), ['lab_napza_id' => 'lab_napza_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriksa()
    {
        return $this->hasOne(JenisPeriksaLab::className(), ['periksa_id' => 'periksa_id']);
    }
}
