<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_rujukan".
 *
 * @property string $referensi_cd
 * @property string $referensi_nm
 * @property string $reff_tp
 * @property string $referensi_root
 * @property string $dr_nm
 * @property string $address
 * @property string $phone
 * @property string $modi_datetime
 * @property string $modi_id
 * @property string $info_01
 * @property string $info_02
 */
class JenisRujukan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jenis_rujukan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['referensi_cd'], 'required'],
            [['address'], 'string'],
            [['modi_datetime'], 'safe'],
            [['referensi_cd', 'reff_tp', 'referensi_root', 'dr_nm', 'phone', 'modi_id'], 'string', 'max' => 20],
            [['referensi_nm', 'info_01', 'info_02'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'referensi_cd' => 'Referensi Cd',
            'referensi_nm' => 'Referensi Nm',
            'reff_tp' => 'Reff Tp',
            'referensi_root' => 'Referensi Root',
            'dr_nm' => 'Dr Nm',
            'address' => 'Address',
            'phone' => 'Phone',
            'modi_datetime' => 'Modi Datetime',
            'modi_id' => 'Modi ID',
            'info_01' => 'Info 01',
            'info_02' => 'Info 02',
        ];
    }
}
