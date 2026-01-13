<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_312".
 *
 * @property integer $no
 * @property string $metoda
 */
class RlRef312 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_312';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'integer'],
            [['metoda'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'metoda' => 'Metoda',
        ];
    }
}
