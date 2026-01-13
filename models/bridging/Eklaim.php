<?php

namespace app\models\bridging;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EklaimPasien;

class Eklaim extends \yii\db\ActiveRecord{

	static public $coder_nik = '123';
	static public $kodeTarifDf = 'CP';
	private $develMode = 1; // 1/0
	private $encriptionKey = '2ad46c458f35a2f222c200e0242cfa298c41dea094c759e51566b944487476d6';
	private $url = 'http://192.168.56.101/E-Klaim/ws.php';

	public static function kelas_rawatOPT(){
		return ['3'=>'REGULER','1'=>'EKSEKUTIF'];
	}

	public static function upgrade_class_classOPT(){
		return [''=>'TIDAK NAIK KELAS','kelas_2'=>'KELAS 2','kelas_1'=>'KELAS 1','vip'=>'VIP'];
	}

	public function payorOPT(){
		return [
			'0'=>'JKN',
			'1'=>'JAMKESDA',
			'2'=>'JAMKESOS',
			'3'=>'PASIEN BAYAR'
		];
	}
	
	public function payor($id){
		$opt = [
			'0'=>'JKN',
			'1'=>'JAMKESDA',
			'2'=>'JAMKESOS',
			'3'=>'PASIEN BAYAR'
		];
		return $opt[$id];
	}


	public static function payor_id($id){
		$opt = ['3', '5', '6', '1'];
		return $opt[$id];
	}
	
	public static function payor_cd($id){
		$opt = ['JKN', '001', 'JKS', '999'];
		return $opt[$id];
	}
	
	public static function cob_cdOPT(){
		return ['0'=>'TIDAK','1'=>'YA'];
	}
	
	public static function icu_indikatorOPT(){
		return ['0'=>'TIDAK','1'=>'YA'];
	}
	
	public static function discharge_statusOPT(){
		return [
                    '1'=>'PERSETUJUAN DOKTER',
                    '2'=>'DIRUJUK',
                    '3'=>'ATAS PERMINTAAN PASIEN',
                    '4'=>'MENINGGAL',
                    '5'=>'LAIN-LAIN'
                ];
	}
	
	private $payLoad, $header;

	public function __construct($metadata=null, $data=null){
		if ($this->develMode==1) $this->url .= '?mode=debug';

		$this->header = array("Content-Type: application/x-www-form-urlencoded");

		if($metadata!=null && $data!=null)
			$this->setPayLoad($metadata, $data);
	}

	public function setPayLoad($metadata, $data){
		$this->payLoad = [
			'metadata' => $metadata,
			'data' => $data
		];
	}

	public function save($runValidation = true, $attributeNames = NULL){
		if (!$this->isNewRecord && (strtoupper($this->jenis_asuransi)=='BPJS' && $this->no_asuransi!='')){
		// 	$metadata = ['method'=>'new_claim'];
		// else
			$metadata = ['method'=>'update_patient',
				'nomor_rm'=>$this->mr];
			$data = [
					'nomor_kartu'=>$this->no_asuransi,
	                // 'nomor_sep'=>$this->nomor_sep,
	                'nomor_rm'=>$this->mr,
	                'nama_pasien'=>$this->nama,
	                'tgl_lahir'=>$this->tanggal_lahir.' 00:00:00',
	                'gender'=>($this->jk == 'Perempuan')? '2':'1',
				];

			$this->setPayLoad($metadata, $data);
			// $response = $this->execute();

			// if ($response->metadata->code==200)
			// 	\Yii::$app->getSession()->setFlash('success', 'Berhasil Merubah data pada E-Klaim');
		}
		return parent::save($runValidation, $attributeNames);
	}

	public function delete(){
		if (!$this->isNewRecord && (strtoupper($this->jenis_asuransi)=='BPJS' && $this->no_asuransi!='')){
			$metadata = ['method'=>"delete_patient"];
			$data = [
				'nomor_rm'=>$this->mr,
				'coder_nik'=>$this->coder_nik
			];
			$this->setPayLoad($metadata, $data);
			// $response = $this->execute();

			// if ($response->metadata->code==200)
			// 	\Yii::$app->getSession()->setFlash('success', 'Berhasil Menghapus data pada E-Klaim');
		}
		return parent::delete();
	}

	private function decrypt($str){
		$key = hex2bin($this->encriptionKey);
		if (mb_strlen($key, "8bit") !== 32) {
			throw new Exception("Needs a 256-bit key!");
		}
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");
		$decoded = base64_decode($str);
		$signature = mb_substr($decoded,0,10,"8bit");
		$iv = mb_substr($decoded,10,$iv_size,"8bit");
		$encrypted = mb_substr($decoded,$iv_size+10,NULL,"8bit");
		$calc_signature = mb_substr(hash_hmac("sha256", $encrypted, $key, true),0,10,"8bit");
		if(!$this->compare($signature,$calc_signature)) {
			return "SIGNATURE_NOT_MATCH";
		}
		$decrypted = openssl_decrypt($encrypted, "aes-256-cbc",	$key, OPENSSL_RAW_DATA,	$iv);
		return $decrypted;
	}

	function encrypt($data) {
		/// make binary representasion of $key
		$key = hex2bin($this->encriptionKey);
		/// check key length, must be 256 bit or 32 bytes
		if (mb_strlen($key, "8bit") !== 32) {
			throw new Exception("Needs a 256-bit key!");
		}
		/// create initialization vector
		$iv_size = openssl_cipher_iv_length("aes-256-cbc");
		$iv = openssl_random_pseudo_bytes($iv_size); // dengan catatan dibawah
		/// encrypt
		$encrypted = openssl_encrypt($data,	"aes-256-cbc",	$key,	OPENSSL_RAW_DATA, $iv);
		$signature = mb_substr(hash_hmac("sha256",	$encrypted,	$key,	true),0,10,"8bit");
		/// combine all, encode, and format
		$encoded = chunk_split(base64_encode($signature.$iv.$encrypted));
		return $encoded;
	}

	function compare($a, $b) {
		if (strlen($a) !== strlen($b)) return false;
		$result = 0;
		for($i = 0; $i < strlen($a); $i ++) {
			$result |= ord($a[$i]) ^ ord($b[$i]);
		}
		return $result == 0;
	}

	private function decode($response){
		if ($this->develMode==0)
		{
			$first = strpos($response, "\n")+1;
			$last = strrpos($response, "\n")-1;
			$response = substr($response, $first, strlen($response) - $first - $last);
			
			$response = $this->decrypt($response);
		}

		return json_decode($response);
	}


	public function execute(){
		$this->payLoad = json_encode($this->payLoad);
		if ($this->develMode==0)
			$this->payLoad = $this->encrypt($this->payLoad);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$this->header);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->payLoad);
		// request dengan curl
		$response = curl_exec($ch);

		return $this->decode($response);
	}

}
