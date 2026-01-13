<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eklaim_icd9cm".
 *
 * @property string $id
 * @property string $long_desc
 * @property string $short_desc
 */
class EklaimIcd9cm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eklaim_icd9cm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['long_desc'], 'string'],
            [['id', 'short_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'long_desc' => 'Long Desc',
            'short_desc' => 'Short Desc',
        ];
    }
}
