<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rm_penunjang".
 *
 * @property string $id
 * @property string $rm_id
 * @property string $path
 * @property string $created
 *
 * @property RekamMedis $rm
 */
class RmPenunjang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rm_penunjang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rm_id'], 'integer'],
            [['path'], 'string'],
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
            'path' => 'Path',
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
