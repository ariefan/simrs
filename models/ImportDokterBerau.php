<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "import_dokter_berau".
 *
 * @property string $nama_dokter
 * @property string $password_default
 */
class ImportDokterBerau extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'import_dokter_berau';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_dokter', 'password_default'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nama_dokter' => 'Nama Dokter',
            'password_default' => 'Password Default',
        ];
    }
}
