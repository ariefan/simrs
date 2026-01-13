<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_31".
 *
 * @property integer $no
 * @property string $jenis_pelayanan
 */
class RlRef31 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_31';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'integer'],
            [['jenis_pelayanan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'jenis_pelayanan' => 'Jenis Pelayanan',
        ];
    }
}
