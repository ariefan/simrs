<?php
use app\models\TarifTp;
use app\models\TarifTpItem;
namespace app\models;

use Yii;

/**
 * This is the model class for table "bayar".
 *
 * @property string $no_invoice
 * @property string $kunjungan_id
 * @property string $mr
 * @property string $nama_pasien
 * @property string $alamat
 * @property string $tanggal_bayar
 * @property string $subtotal
 * @property integer $diskon
 * @property string $total
 * @property string $kembali
 * @property string $bayar
 * @property string $created
 *
 * @property Kunjungan $kunjungan
 * @property BayarObat[] $bayarObats
 * @property BayarTindakan[] $bayarTindakans
 */
class Bayar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $items;
    public static function tableName()
    {
        return 'bayar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no_invoice'], 'required'],
            [['kunjungan_id', 'subtotal', 'diskon', 'total'], 'integer'],
            [['tanggal_bayar', 'created', 'kembali', 'bayar'], 'safe'],
            [['no_invoice'], 'string', 'max' => 20],
            [['mr'], 'string', 'max' => 25],
            [['nama_pasien', 'alamat'], 'string', 'max' => 255],
            [['kunjungan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kunjungan::className(), 'targetAttribute' => ['kunjungan_id' => 'kunjungan_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'no_invoice' => 'No Invoice',
            'kunjungan_id' => 'Kunjungan ID',
            'mr' => 'Mr',
            'nama_pasien' => 'Nama Pasien',
            'alamat' => 'Alamat',
            'tanggal_bayar' => 'Tanggal Bayar',
            'subtotal' => 'Subtotal',
            'diskon' => 'Diskon',
            'total' => 'Total',
            'kembali' => 'Kembali',
            'bayar' => 'Bayar',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKunjungan()
    {
        return $this->hasOne(Kunjungan::className(), ['kunjungan_id' => 'kunjungan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBayarObats()
    {
        return $this->hasMany(BayarObat::className(), ['no_invoice' => 'no_invoice']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBayarTindakans()
    {
        return $this->hasMany(BayarTindakan::className(), ['no_invoice' => 'no_invoice']);
    }

    public function createNoInvoice()
    {
        $connection = Yii::$app->db;
        $klinik_id = Yii::$app->user->identity->klinik_id;
        $sql = "SELECT 
                  LPAD(
                    IFNULL(
                      MAX(
                        CONVERT(SUBSTR(no_invoice, -6), UNSIGNED INTEGER)
                      ),
                      0
                    ) + 1,
                    6,
                    0
                  ) AS NEXT_INV 
                FROM
                  bayar JOIN kunjungan USING (kunjungan_id) 
                WHERE klinik_id = $klinik_id ";
        $command = $connection->createCommand($sql);
        $nextmr = $command->queryOne();
        return "INV".$klinik_id.$nextmr['NEXT_INV'];
    }

    public function getBayarObat($kunjungan_id, $lunas=1)
    {
        $connection = Yii::$app->db;    
        $sql = "
          SELECT 
            r.tgl_periksa as tgl,a.item_cd as tarif_id,obat_id, nama_obat as nama_merk, jumlah, 

            IF(CB.harga_obat='jual_1',a.item_price,a.item_price_2) as harga_jual
          FROM 
            rekam_medis r 
            JOIN kunjungan K ON r.kunjungan_id = K.kunjungan_id 
            JOIN cara_bayar CB on CB.cara_id = K.cara_id
            JOIN rm_obat o USING (rm_id)
            JOIN inv_item_master a ON a.`item_cd` = o.`obat_id`
          WHERE K.kunjungan_id = $kunjungan_id
          ";

        if($lunas==-1)
          $sql .= ' and obat_id not in (select BT.obat_id from bayar B, bayar_obat BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = r.kunjungan_id)';
        elseif($lunas==1)
          $sql .= ' and obat_id in (select BT.obat_id from bayar B, bayar_obat BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = r.kunjungan_id)';

        // echo $sql;

        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public function getBayarObatRacik($kunjungan_id, $lunas=1)
    {
        $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                  a.item_cd as tarif_id,
                  r.tgl_periksa as tgl,obat_id, nama_obat as nama_merk, o.jumlah, 
                  IF(CB.harga_obat='jual_1',a.item_price,a.item_price_2) as harga_jual
                FROM
                  rekam_medis r 
                  JOIN rm_obat_racik USING (rm_id) 
                  JOIN rm_obat_racik_komponen o USING (racik_id)
                  JOIN inv_item_master a ON a.`item_cd` = o.`obat_id`
                  JOIN kunjungan K ON r.kunjungan_id = K.kunjungan_id 
                  JOIN cara_bayar CB on CB.cara_id = K.cara_id
                WHERE r.kunjungan_id = $kunjungan_id";

        if($lunas==-1)
          $sql .= ' and obat_id not in (select BT.obat_id from bayar B, bayar_obat BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = r.kunjungan_id)';
        elseif($lunas==1)
          $sql .= ' and obat_id in (select BT.obat_id from bayar B, bayar_obat BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = r.kunjungan_id)';


        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public function getBayarTindakan($kunjungan_id, $lunas=1)
    {
        $connection = Yii::$app->db;

        $kunjungan = Kunjungan::findOne($kunjungan_id);

        // $whereKelas = '';
        // if ($kunjungan->kelas_cd!=null){
        //   $whereKelas = ' = "'.$kunjungan->kelas_cd.'" ';
        //   $whereKelas = "AND (kelas_cd ".$whereKelas.")";
        // }
        
        $sql = "SELECT 
                  rekam_medis.tgl_periksa as tgl,
                  tarif_tindakan_id as tarif_id,
                  tindakan_cd AS tindakan_id,
                  nama_tindakan,
                  jumlah,
                  tarif 
                FROM
                  rekam_medis 
                  JOIN rm_tindakan a USING (rm_id) 
                  JOIN tarif_tindakan b 
                    ON a.`tarif_id` = b.`tarif_tindakan_id` 
                WHERE kunjungan_id = $kunjungan_id";

        if($lunas==-1)
          $sql .= ' and tindakan_cd not in (select BT.tindakan_id from bayar B, bayar_tindakan BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = rekam_medis.kunjungan_id)';
        elseif($lunas==1)
          $sql .= ' and tindakan_cd in (select BT.tindakan_id from bayar B, bayar_tindakan BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = rekam_medis.kunjungan_id)';

        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public function getBayarRadiologi($kunjungan_id, $lunas=1)
    {
        $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                RM.tgl_periksa as tgl,
                T.tarif_unitmedis_id AS tarif_id,
                T.medicalunit_cd AS medicalunit_id,
                R.nama as nama_radio,
                T.tarif,
                R.jumlah
                FROM
                  rm_rad R,
                  rekam_medis RM,
                  tarif_unitmedis T
                WHERE RM.kunjungan_id = $kunjungan_id AND
                  R.rm_id = RM.rm_id AND
                  R.tarif_id = T.tarif_unitmedis_id";
        if($lunas==-1)
          $sql .= ' and T.medicalunit_cd not in (select BT.rad_cd from bayar B, bayar_rad BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = RM.kunjungan_id)';
        elseif($lunas==1)
          $sql .= ' and T.medicalunit_cd in (select BT.rad_cd from bayar B, bayar_rad BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = RM.kunjungan_id)';
        
        // echo $sql;exit;
        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public function getBayarLab($kunjungan_id, $lunas=1)
    {
        $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                RM.tgl_periksa as tgl,
                T.tarif_unitmedis_id AS tarif_id,
                T.medicalunit_cd,
                R.nama as nama_lab,
                T.tarif,
                R.jumlah
                FROM
                  rm_lab R,
                  rekam_medis RM,
                  tarif_unitmedis T
                WHERE RM.kunjungan_id = $kunjungan_id AND
                  R.rm_id = RM.rm_id AND
                  R.tarif_id = T.tarif_unitmedis_id ";

        if($lunas==-1)  
          $sql .= ' and T.medicalunit_cd not in (select BT.lab_cd from bayar B, bayar_lab BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = RM.kunjungan_id)';
        elseif($lunas==1)
          $sql .= ' and T.medicalunit_cd in (select BT.lab_cd from bayar B, bayar_lab BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = RM.kunjungan_id)';

        $command = $connection->createCommand($sql);
        return $command->queryAll();
    }

    public function getBayarRuang($kunjungan_id, $lunas=1)
    {
      if ($kunjungan = Kunjungan::findOne($kunjungan_id))
        if($kunjungan->medunit_cd=='' && $kunjungan->jam_selesai!=null){

          // if($lunas==0){
            $tarifKelas = TarifKelas::find()->where(['kelas_cd'=>$kunjungan->kelas_cd])->one();

            $nHari = ceil(abs(strtotime($kunjungan->jam_selesai) - strtotime($kunjungan->jam_masuk)) / (60 * 60 * 24));

            if($lunas==0)
              return ['ruang_nm'=>$kunjungan->ruang0->ruang_nm,'tarif'=>$tarifKelas->tarif, 'nHari'=>$nHari,'seq_no'=>$tarifKelas->seq_no];
            if ($lunas==1){
              if(BayarKelas::find()->where(['no_invoice'=>$this->no_invoice])->count()>0)
                return ['ruang_nm'=>$kunjungan->ruang0->ruang_nm,'tarif'=>$tarifKelas->tarif, 'nHari'=>$nHari,'seq_no'=>$tarifKelas->seq_no];
            }
            if ($lunas==-1){
              if(BayarKelas::find()->where(['no_invoice'=>$this->no_invoice])->count()==0)
                return ['ruang_nm'=>$kunjungan->ruang0->ruang_nm,'tarif'=>$tarifKelas->tarif, 'nHari'=>$nHari,'seq_no'=>$tarifKelas->seq_no];
            }
          // }
          // if($lunas==-1){
          //   $tarifKelas = TarifKelas::find()->where(['kelas_cd'=>$kunjungan->kelas_cd])->one();

          //   $nHari = ceil(abs(strtotime($kunjungan->jam_selesai) - strtotime($kunjungan->jam_masuk)) / (60 * 60 * 24));

          //   if(BayarKelas::find()->where([])->count()==1)
          //   return ['ruang_nm'=>$kunjungan->ruang0->ruang_nm,'tarif'=>$tarifKelas->tarif, 'nHari'=>$nHari,'seq_no'=>$tarifKelas->seq_no];
          // }
      }

      return ['ruang_nm'=>'', 'tarif'=>0, 'nHari'=>0,'seq_no'=>''];
    }

    public function getBayarPaket($kunjungan_id, $lunas=1)
    {
        $connection = Yii::$app->db;     
        
        $sql = "SELECT 
                K.jam_masuk AS tgl,
                G.tarif_general_id,
                G.tarif_general_id AS tarif_id,
                G.tarif_nm as nama_paket,
                G.tarif 
                FROM
                 kunjungan K,
                 tarif_general G
                WHERE K.kunjungan_id = $kunjungan_id AND
                  G.kelas_cd IS NULL AND
                  G.auto_add = '1' AND
                  G.medical_tp = K.medunit_cd";

        if($lunas==-1)
        {
          $sql .= ' and G.tarif_general_id not in (select BT.tarif_general_id from bayar B, bayar_general BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = K.kunjungan_id)';
          $daftar = TarifGeneral::find()->select(['now() as tgl','tarif_general_id','tarif_general_id AS tarif_id','tarif_nm as nama_paket','tarif'])->where(['tarif_general_id'=>TarifGeneral::$pendaftaranPeserta])->andWhere(['not in','tarif_general_id', Bayar::find()->select('tarif_general_id')->leftJoin('bayar_general','bayar.no_invoice = bayar_general.no_invoice')->where(['bayar.kunjungan_id'=>$kunjungan_id])])->asArray()->one();
        }
        elseif($lunas==1)
        {
          $sql .= ' and G.tarif_general_id in (select BT.tarif_general_id from bayar B, bayar_general BT where B.no_invoice = BT.no_invoice and B.kunjungan_id = K.kunjungan_id)';
          $daftar = TarifGeneral::find()->select(['now() as tgl','tarif_general_id','tarif_general_id AS tarif_id','tarif_nm as nama_paket','tarif'])->where(['tarif_general_id'=>TarifGeneral::$pendaftaranPeserta])->andWhere(['in','tarif_general_id', Bayar::find()->select('tarif_general_id')->leftJoin('bayar_general','bayar.no_invoice = bayar_general.no_invoice')->where(['bayar.kunjungan_id'=>$kunjungan_id])])->asArray()->one();
        }
        $command = $connection->createCommand($sql);

        $paket = $command->queryAll();
        if(count($daftar)>=1)
          array_push($paket, $daftar);
        return $paket;
    }

    public function getTotalPemasukanHariIni($klinik_id)
    {
        $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                  SUM(total) as total
                FROM
                  bayar JOIN kunjungan USING (kunjungan_id)
                WHERE klinik_id = $klinik_id AND DATE(tanggal_bayar) = DATE(NOW()) ";
                
        $command = $connection->createCommand($sql);
        return $command->queryOne();
    }

    public function getTotalPemasukanBulanIni($klinik_id)
    {
        $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                  SUM(total) as total
                FROM
                  bayar JOIN kunjungan USING (kunjungan_id)
                WHERE klinik_id = $klinik_id AND MONTH(tanggal_bayar) = MONTH(NOW()) ";
                
        $command = $connection->createCommand($sql);
        return $command->queryOne();
    }

    public function getTotalPemasukanObatHariIni($klinik_id)
    {
      $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                  SUM(bayar_obat.harga_total) as total
                FROM
                  bayar_obat 
                  JOIN bayar USING (no_invoice) 
                  JOIN kunjungan USING (kunjungan_id)
                WHERE klinik_id = $klinik_id AND DATE(tanggal_bayar) = DATE(NOW()) ";
                
        $command = $connection->createCommand($sql);
        return $command->queryOne();
    }

    public function getTotalPemasukanTindakanHariIni($klinik_id)
    {
      $connection = Yii::$app->db;    
        
        $sql = "SELECT 
                  SUM(bayar_tindakan.harga) as total
                FROM
                  bayar_tindakan
                  JOIN bayar USING (no_invoice) 
                  JOIN kunjungan USING (kunjungan_id)
                WHERE klinik_id = $klinik_id AND DATE(tanggal_bayar) = DATE(NOW()) ";
                
        $command = $connection->createCommand($sql);
        return $command->queryOne();
    }

    public function getTerbilang($x)
    {
      $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
      if ($x < 12)
        return " " . $abil[$x];
      elseif ($x < 20)
        return $this->getTerbilang($x - 10) . "Belas";
      elseif ($x < 100)
        return $this->getTerbilang($x / 10) . " Puluh" . $this->getTerbilang($x % 10);
      elseif ($x < 200)
        return " seratus" . $this->getTerbilang($x - 100);
      elseif ($x < 1000)
        return $this->getTerbilang($x / 100) . " Ratus" . $this->getTerbilang($x % 100);
      elseif ($x < 2000)
        return " seribu" . $this->getTerbilang($x - 1000);
      elseif ($x < 1000000)
        return $this->getTerbilang($x / 1000) . " Ribu" . $this->getTerbilang($x % 1000);
      elseif ($x < 1000000000)
        return $this->getTerbilang($x / 1000000) . " Juta" . $this->getTerbilang($x % 1000000);
    }

    public function getDetails($trx_tarif_seqno, $tarif_tp, $insurance_cd, $kelas_cd = NULL ){
      $tarif_seqno = (int)$trx_tarif_seqno;

      // echo '<pre>'; print_r(compact('trx_tarif_seqno', 'tarif_tp', 'insurance_cd', 'kelas_cds')); echo '</pre>';
        $return = ['SARANA'=>'','SPESIALIS'=>'','PELAKSANA'=>''];
        $tarif_tp_no = TarifTp::find()->where(compact('tarif_seqno', 'tarif_tp', 'insurance_cd', 'kelas_cd'))->one();
        // echo '<pre>'; print_r($tarif_tp_no); echo '</pre>';
        if (isset($tarif_tp_no->tariftp_no))
        {
            $tarif_tp_no = $tarif_tp_no->tariftp_no;

            $return['SARANA'] = TarifTpItem::find()->where(['tariftp_no'=>$tarif_tp_no,'item_nm'=>'JASA SARANA'])->one();
            $return['SPESIALIS'] = TarifTpItem::find()->where(['tariftp_no'=>$tarif_tp_no,'item_nm'=>'JASA SPESIALIS'])->one();
            $return['PELAKSANA'] = TarifTpItem::find()->where(['tariftp_no'=>$tarif_tp_no,'item_nm'=>'JASA PELAKSANA'])->one();

            return $return; 
        }
        else{
          return $return;
        }
        
    }

}
