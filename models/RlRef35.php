<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_35".
 *
 * @property string $no
 * @property string $jenis_kegiatan
 */
class RlRef35 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_35';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'string', 'max' => 4],
            [['jenis_kegiatan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no' => 'No',
            'jenis_kegiatan' => 'Jenis Kegiatan',
        ];
    }
}
