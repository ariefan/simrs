<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inv_supplier".
 *
 * @property string $supplier_cd
 * @property string $supplier_nm
 * @property string $address
 * @property string $city
 * @property string $province
 * @property string $postcode
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property string $email
 * @property string $npwp
 * @property string $pic
 */
class InvSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['supplier_cd'], 'required'],
            [['address'], 'string'],
            [['supplier_cd', 'postcode', 'phone', 'mobile', 'fax', 'npwp'], 'string', 'max' => 20],
            [['supplier_nm', 'city', 'province', 'email'], 'string', 'max' => 100],
            [['pic'],'file','extensions'=>'jpg,png']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'supplier_cd' => 'Kode',
            'supplier_nm' => 'Nama',
            'address' => 'Alamat',
            'city' => 'Kota',
            'province' => 'Provinsi',
            'postcode' => 'Kode Pos',
            'phone' => 'Telepon',
            'mobile' => 'HP',
            'fax' => 'Fax',
            'email' => 'Email',
            'npwp' => 'NPWP',
            'pic' => 'Foto',
        ];
    }

    public function upload($id)
    {
        if (!empty($this->pic)&&$this->validate()) {
            $this->pic->saveAs('foto_supplier/' . $id . '.' . $this->pic->extension);
        }
        return true;
    }
}
