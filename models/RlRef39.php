<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_39".
 *
 * @property string $no
 * @property string $jenis_tindakan
 */
class RlRef39 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_39';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'string', 'max' => 30],
            [['jenis_tindakan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'jenis_tindakan' => 'Jenis Tindakan',
        ];
    }
}
