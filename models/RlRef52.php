<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rl_ref_52".
 *
 * @property integer $no
 * @property string $jenis_kegiatan
 */
class RlRef52 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rl_ref_52';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no'], 'required'],
            [['no'], 'integer'],
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
