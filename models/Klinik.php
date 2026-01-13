<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "klinik".
 *
 * @property integer $klinik_id
 * @property string $klinik_nama
 * @property string $region_cd
 * @property string $kode_pos
 * @property string $fax
 * @property string $email
 * @property string $website
 * @property string $alamat
 * @property string $nomor_telp_1
 * @property string $nomor_telp_2
 * @property string $kepala_klinik
 * @property string $maximum_row
 * @property integer $luas_tanah
 * @property integer $luas_bangunan
 * @property string $izin_no
 * @property string $izin_tgl
 * @property string $izin_oleh
 * @property string $izin_sifat
 * @property string $izin_masa_berlaku
 *
 * @property KlinikCredit[] $klinikCredits
 * @property Kunjungan[] $kunjungans
 * @property Obat[] $obats
 * @property Pasien[] $pasiens
 * @property Tindakan[] $tindakans
 * @property User[] $users
 */
class Klinik extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'klinik';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alamat', 'izin_sifat'], 'string'],
            [['maximum_row', 'luas_tanah', 'luas_bangunan'], 'integer'],
            [['izin_tgl', 'izin_masa_berlaku'], 'safe'],
            [['klinik_nama', 'kepala_klinik', 'izin_no', 'izin_oleh'], 'string', 'max' => 255],
            [['region_cd'], 'string', 'max' => 20],
            [['kode_pos'], 'string', 'max' => 10],
            [['fax', 'email', 'nomor_telp_1', 'nomor_telp_2'], 'string', 'max' => 50],
            [['website'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'klinik_id' => 'Klinik ID',
            'klinik_nama' => 'Klinik Nama',
            'region_cd' => 'Region Cd',
            'kode_pos' => 'Kode Pos',
            'fax' => 'Fax',
            'email' => 'Email',
            'website' => 'Website',
            'alamat' => 'Alamat',
            'nomor_telp_1' => 'Nomor Telp 1',
            'nomor_telp_2' => 'Nomor Telp 2',
            'kepala_klinik' => 'Kepala Klinik',
            'maximum_row' => 'Maximum Row',
            'luas_tanah' => 'Luas Tanah',
            'luas_bangunan' => 'Luas Bangunan',
            'izin_no' => 'Izin No',
            'izin_tgl' => 'Izin Tgl',
            'izin_oleh' => 'Izin Oleh',
            'izin_sifat' => 'Izin Sifat',
            'izin_masa_berlaku' => 'Izin Masa Berlaku',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKlinikCredits()
    {
        return $this->hasMany(KlinikCredit::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunjungans()
    {
        return $this->hasMany(Kunjungan::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObats()
    {
        return $this->hasMany(Obat::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPasiens()
    {
        return $this->hasMany(Pasien::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTindakans()
    {
        return $this->hasMany(Tindakan::className(), ['klinik_id' => 'klinik_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['klinik_id' => 'klinik_id']);
    }
}
