<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_314".
 *
 * @property integer $no
 * @property string $jenis_spesialisasi
 */
class RlRef314 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_314';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'integer'],
            [['jenis_spesialisasi'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'jenis_spesialisasi' => 'Jenis Spesialisasi',
        ];
    }
}
