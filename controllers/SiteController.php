<?php

namespace app\controllers;

use Yii;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Klinik;
use app\models\ContactForm;
use app\models\Bayar;
use app\models\Dokter;
use app\models\RekamMedis;
use app\models\Kunjungan;
use app\models\ForgotForm;
use app\models\ResetForm;
use app\models\User;
use app\models\UserToken;
use app\models\Tindakan;
use app\models\TarifTindakan;
use app\models\TarifTpItem;
use app\models\TarifTp;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','logout'],
                'rules' => [
                    [
                        'actions' => ['logout','index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
              'class' => 'yii\authclient\AuthAction',
              'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }

    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role;
        if($role=='10')
            return $this->redirect(['dashboard/index']);
        if($role=='11')
            return $this->redirect(['kunjungan/index?jenis=rj']);
        if($role=='12')
            return $this->redirect(['kunjungan/bayar']);
        if($role=='13')
            return $this->redirect(['kunjungan/farmasi']);
        if($role=='14')
            return $this->redirect(['kunjungan/tracking']);
        if($role=='25')
            return $this->redirect(['kunjungan/pemeriksaan']);
        if($role=='15')
            return $this->redirect(['rm-lab/index']);
        if($role=='16')
            return $this->redirect(['rm-rad/index']);
        if($role=='17')
            return $this->redirect(['kunjungan/pemeriksaan']);
        if($role=='18')
            return $this->redirect(['rekam-medis/index']);
        if($role=='19')
            return $this->redirect(['inv-pos-item/index']);
        if($role=='22')
            return $this->redirect(['gizi-diet/permintaan']);

        $dokter= new Dokter();
        $complete_profile = $dokter->isNothingEmpty();

        $model = new RekamMedis();

        $this->layout = 'main_no_portlet';
        $Bayar = new Bayar();
        //$total_hari = $Bayar->getTotalPemasukanHariIni(Yii::$app->user->identity->klinik_id);
        //$total_bulan = $Bayar->getTotalPemasukanBulanIni(Yii::$app->user->identity->klinik_id);
        $pasien_bulan = Kunjungan::find()->where(['klinik_id'=>Yii::$app->user->identity->klinik_id,'MONTH(tanggal_periksa)'=>date('m')])->count();        
        $pasien = Kunjungan::find()->where(['klinik_id'=>Yii::$app->user->identity->klinik_id,'tanggal_periksa'=>date('Y-m-d')])->count(); 

        $kunjungan=[];$farmasi=[];$pembayaran=[];$selesai = [];
        if(Yii::$app->user->identity->role=='25'){
            //$kunjungan = Kunjungan::find()->joinWith('rekamMedis',true,'LEFT JOIN')->joinWith('mr0')->where(['kunjungan.dokter_periksa'=>Yii::$app->user->identity->id])->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']])->asArray()->all();
            $kunjungan = Kunjungan::find()->joinWith('rekamMedis',true,'LEFT JOIN')->joinWith('mr0')->where(['kunjungan.dokter_periksa'=>Yii::$app->user->identity->id])->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']])->asArray()->all();

            $farmasi = Kunjungan::find()->joinWith('mr0')->where(['kunjungan.dokter_periksa'=>Yii::$app->user->identity->id,'status'=>'antri obat'])->asArray()->all();
            $pembayaran = Kunjungan::find()->joinWith('mr0')->where(['kunjungan.dokter_periksa'=>Yii::$app->user->identity->id,'status'=>'antri bayar'])->asArray()->all();
            $selesai = Kunjungan::find()->joinWith('rekamMedis',true,'LEFT JOIN')->joinWith('mr0')->joinWith('bayar')->where(['kunjungan.dokter_periksa'=>Yii::$app->user->identity->id,'status'=>'selesai'])->asArray()->all();
        } elseif(Yii::$app->user->identity->role=='20' or Yii::$app->user->identity->role=='10') {
            $kunjungan = Kunjungan::find()->joinWith('rekamMedis',true,'LEFT JOIN')->joinWith('mr0')->where(['kunjungan.klinik_id'=>Yii::$app->user->identity->klinik_id])->andFilterWhere(['or',['status'=>'antri'],['status'=>'diperiksa']])->asArray()->all();
            $farmasi = Kunjungan::find()->joinWith('mr0')->where(['kunjungan.klinik_id'=>Yii::$app->user->identity->klinik_id,'status'=>'antri obat'])->asArray()->all();
            $pembayaran = Kunjungan::find()->joinWith('mr0')->where(['kunjungan.klinik_id'=>Yii::$app->user->identity->klinik_id,'status'=>'antri bayar'])->asArray()->all();
            $selesai = Kunjungan::find()->joinWith('rekamMedis',true,'LEFT JOIN')->joinWith('mr0')->joinWith('bayar')->where(['kunjungan.klinik_id'=>Yii::$app->user->identity->klinik_id,'status'=>'selesai'])->orderBy('tanggal_periksa DESC')->limit(5)->asArray()->all();
        }
        return $this->render('index',compact('total_hari','total_bulan','pasien_bulan','pasien','kunjungan','farmasi','pembayaran','selesai','complete_profile'));
    }

    public function actionLogin()
    {
        $this->layout = 'login_sb';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login_sb', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $this->layout = 'login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new SignupForm();
        $model->scenario = 'signup';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $klinikModel = new Klinik;
            $klinikModel->klinik_nama = "Klinik ".$model->username;
            if ($klinikModel->save())
            {
                $model->role = 20;
                $model->apps = 'WEB';
                $model->klinik_id = $klinikModel->klinik_id;
                $s = $model->signup();
                $modelLogin = new LoginForm;
                $modelLogin->username = $model->username;
                $modelLogin->password = $model->password;

                $drModel = new Dokter;
                $drModel->user_id = $s->id;
                $drModel->save();

                $modelLogin->login();
            }
            return $this->redirect(['site/index']);
        }
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function oAuthSuccess($client) {
      // get user data from client
      if($userAttributes = $client->getUserAttributes())
      {
        //apps = 'FB' OR apps_id=':appsId' OR
        //print_r($userAttributes);
        //die;
        $userModel = User::Find()->where("apps_id=:appsId OR email =:e", [':appsId'=>$userAttributes['id'], ':e'=>$userAttributes['email']])->all();
        //print_r($userModel);
        //die;
        if (count($userModel)==0) //SIGNUP
        {
            $model = new SignupForm();
            $model->scenario = 'signup';
            $model->username = $userAttributes['email'];
            $model->email = $userAttributes['email'];
            $model->apps = 'FB';
            $model->apps_id = $userAttributes['id'];
            $pss = substr( md5(rand()), 0, 10);
            $model->password = $pss;
            $model->password2 = $pss;

            $klinikModel = new Klinik;
            $klinikModel->klinik_nama = "Klinik ".$userAttributes['name'];

            if ($klinikModel->save()) 
            {
                $model->role = 20;
                $model->klinik_id = $klinikModel->klinik_id;
                $s = $model->signup();

                $drModel = new Dokter;
                $drModel->user_id = $s->id;
                $drModel->nama = "Dr. ".$userAttributes['name'];
                $drModel->save();

                $modelLogin = new LoginForm;
                $modelLogin->username = $model->username;
                $modelLogin->password = $model->password;
                $modelLogin->login();
            }
            
        }
        else //SIGNIN
        {
            $modelLogin = new LoginForm;
            $modelLogin->username = $userAttributes['email'];
            $modelLogin->password = 'randomPassword';
            $modelLogin->login(true);
        }

        return $this->redirect(['site/index']);
      }
    }

    public function actionReset($token)
    {
        $this->layout = 'login';
        $reset = new ResetForm();
        // get user token and check expiration
        $userToken = new UserToken();
        $userToken = $userToken::findByToken($token, $userToken::TYPE_PASSWORD_RESET);
        if (!$userToken) {
            return $this->render('reset', ["invalidToken" => true]);
        }

        // get user and set "reset" scenario
        $success = false;
        $user = new User();
        $user = $user::findOne($userToken->user_id);

        // load post data and reset user password
        if ($reset->load(Yii::$app->request->post())) {
            $reset->resetPasswordByToken($userToken->user_id);
            $userToken->delete();
            $success = true;
        }

        return $this->render('reset', compact("user", "success","reset"));
    }

    public function actionForgot()
    {
        $this->layout = 'login';
        /** @var \amnah\yii2\user\models\forms\ForgotForm $model */

        // load post data and send email
        $model = new ForgotForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendForgotEmail()) {
            // set flash (which will show on the current page)
            Yii::$app->session->setFlash("Forgot-success", "Instruksi Telah dikirimkan ke Email Anda");
        }

        return $this->render("forgot", compact("model"));
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionTerms()
    {
        $this->layout = 'terms';
        return $this->render('terms');
    }

    public function actionBantuan()
    {
        return $this->render('bantuan');
    }

    /*public function actionInjekTarif(){
        ini_set('max_execution_time', 3000);
        $tarif = Tindakan::find()->all();

        $arrKelas = [NULL, 'KL01','KL02','KL03','KL04','KL05','KL06','KLVIP'];
        $xKelas =0;
        $x = 0;
        foreach ($tarif as $key => $value) 
            if($value->tindakan_root!='')
        {
            for ($i=0; $i < count($arrKelas); $i++) { 
                $modelTT = new TarifTindakan();
                $modelTT->kelas_cd = $arrKelas[$i];
                $modelTT->tarif = 100000;
                $modelTT->tarif_tindakan_id = $x++;
                $modelTT->treatment_cd = $value->tindakan_cd;
                $modelTT->save();
                print_r($modelTT->errors);
                $modelTTP = new TarifTp();
                $modelTTP->tariftp_nm =$value->nama_tindakan;
                $modelTTP->kelas_cd = $modelTT->kelas_cd;
                $modelTTP->tarif_total = 100000;
                $modelTTP->trx_tarif_seqno = $value->tindakan_cd;
                $modelTTP->save();
                print_r($modelTTP->errors);
            }
            // $modelTT = new TarifTindakan();
            // $modelTT->kelas_cd = $arrKelas[$i];
            // $modelTT->tarif = 100000;
            // $modelTT->tarif_tindakan_id = $x++;
            // $modelTT->treatment_cd = $value->tindakan_cd;
            // $modelTT->save();
            // print_r($modelTT->errors);

            // $xKelas++;
            // if($xKelas>=count($arrKelas))
            //     $xKelas = 0;

            
        }
  



        $tarifT = TarifTp::find()->all();
        $x = 1;
        foreach ($tarifT as $key => $value) 
        {

            $modelTT = new TarifTpItem();
            $modelTT->seq_no = $x++;
            $modelTT->tariftp_no = $value->tariftp_no;
            $modelTT->quantity = 1;
            $modelTT->item_nm = ($x % 2 == 0)?"JASA SARANA":"JASA PELAKSANA";
            $modelTT->tarif_item = ($x % 2 == 0)?30000:70000;            
            $modelTT->save();
            print_r($modelTT->errors);
            $modelTT = new TarifTpItem();
            $modelTT->seq_no = $x++;
            $modelTT->tariftp_no = $value->tariftp_no;
            $modelTT->quantity = 1;
            $modelTT->item_nm = ($x % 2 == 0)?"JASA SARANA":"JASA PELAKSANA";
            $modelTT->tarif_item = ($x % 2 == 0)?30000:70000;            
            $modelTT->save();
            print_r($modelTT->errors);
        }

    }*/
}
