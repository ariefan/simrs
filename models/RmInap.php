<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_inap".
 *
 * @property string $id
 * @property string $rm_id
 * @property string $anamnesis
 * @property string $pemeriksaan_fisik
 * @property string $assesment
 * @property string $plan
 * @property string $created
 *
 * @property RekamMedis $rm
 */
class RmInap extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_inap';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rm_id'], 'integer'],
            [['anamnesis', 'pemeriksaan_fisik', 'assesment', 'plan'], 'string'],
            [['created'], 'safe'],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedis::className(), 'targetAttribute' => ['rm_id' => 'rm_id']],
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
            'anamnesis' => 'Anamnesis',
            'pemeriksaan_fisik' => 'Pemeriksaan Fisik',
            'assesment' => 'Assesment',
            'plan' => 'Plan',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRm()
    {
        return $this->hasOne(RekamMedis::className(), ['rm_id' => 'rm_id']);
    }
}
