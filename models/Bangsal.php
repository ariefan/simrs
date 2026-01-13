<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bangsal".
 *
 * @property string $bangsal_cd
 * @property string $bangsal_nm
 *
 * @property Ruang[] $ruangs
 */
class Bangsal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bangsal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bangsal_cd'], 'required'],
            [['bangsal_cd'], 'string', 'max' => 20],
            [['bangsal_nm'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bangsal_cd' => 'Bangsal Cd',
            'bangsal_nm' => 'Bangsal Nm',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuangs()
    {
        return $this->hasMany(Ruang::className(), ['bangsal_cd' => 'bangsal_cd']);
    }
}
