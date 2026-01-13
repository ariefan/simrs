<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jadwal".
 *
 * @property integer $jadwal_id
 * @property integer $user_id
 * @property string $medunit_cd
 * @property string $day_tp
 * @property string $time_start
 * @property string $time_end
 * @property string $note
 *
 * @property User $user
 * @property UnitMedis $medunitCd
 */
class Jadwal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jadwal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['day_tp', 'note'], 'string'],
            [['time_start', 'time_end'], 'safe'],
            [['medunit_cd'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['medunit_cd'], 'exist', 'skipOnError' => true, 'targetClass' => UnitMedis::className(), 'targetAttribute' => ['medunit_cd' => 'medunit_cd']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jadwal_id' => 'Kode Jadwal',
            'user_id' => 'Dokter',
            'medunit_cd' => 'Unit Medis',
            'day_tp' => 'Hari',
            'time_start' => 'Jam Mulai',
            'time_end' => 'Jam Selesai',
            'note' => 'Catatan',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Dokter::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedunitCd()
    {
        return $this->hasOne(UnitMedis::className(), ['medunit_cd' => 'medunit_cd']);
    }

    public static function tanggal_indo($tanggal, $cetak_hari = false)
    {
        $hari = array ( 1 =>'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                );
                
        $bulan = array (1 =>'Januari',
                    'Februari',
                    'Maret',
                    'April',
                    'Mei',
                    'Juni',
                    'Juli',
                    'Agustus',
                    'September',
                    'Oktober',
                    'November',
                    'Desember'
                );
        $split    = explode('-', $tanggal);
        $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
        
        if ($cetak_hari) {
            $num = date('N', strtotime($tanggal));
            return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
    }
}
