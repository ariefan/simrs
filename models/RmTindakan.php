<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_tindakan".
 *
 * @property string $id
 * @property string $rm_id
 * @property integer $tindakan_id
 *
 * @property RekamMedis $rm
 * @property Tindakan $tindakan
 */
class RmTindakan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_tindakan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rm_id','jumlah'], 'integer'],
            [['nama_tindakan', 'tindakan_cd','tarif_id'],'string','max'=>255],
            [['rm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RekamMedis::className(), 'targetAttribute' => ['rm_id' => 'rm_id']],
            [['tindakan_cd'], 'exist', 'skipOnError' => true, 'targetClass' => Tindakan::className(), 'targetAttribute' => ['tindakan_cd' => 'tindakan_cd']],
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
            'tindakan_cd' => 'Tindakan',
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
    public function getTindakan()
    {
        return $this->hasOne(Tindakan::className(), ['tindakan_id' => 'tindakan_id']);
    }

    public function getDokter()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}
