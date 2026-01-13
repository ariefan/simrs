<?php

namespace app\controllers;

use Yii;
use app\models\OrderSupplier;
use app\models\OrderSupplierDetail;
use app\models\OrderSupplierSearch;
use app\models\Supplier;
use app\models\User;
use app\models\InvItemMove;
use app\components\AccessRule;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * OrderSupplierController implements the CRUD actions for OrderSupplier model.
 */
class OrderSupplierController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                   'class' => AccessControl::className(),
                   'ruleConfig' => [
                       'class' => AccessRule::className(),
                   ],
                   'only' => ['index','create', 'update', 'delete','approve','cancel-approve','receive','cancel-receive'],
                   'rules' => [
                       [
                           'actions' => ['index','create', 'update', 'delete','approve','cancel-approve','receive','cancel-receive'],
                           'allow' => true,
                           'roles' => [
                               User::ROLE_INVENTORY,
                           ],
                       ]
                   ],
            ],    
        ];
    }

    /**
     * Lists all OrderSupplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSupplierSearch();

        $role = (string)Yii::$app->user->identity->role;
        if ($role[0]=='2')
            $searchModel->cabang_id = Yii::$app->user->identity->cabang->cabang_nama;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single OrderSupplier model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $item_exist = OrderSupplierDetail::find()
            ->joinWith('barang')
            ->where(['order_id'=>$id])->asArray()->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'item_exist'=>$item_exist
        ]);
    }

    /**
     * Creates a new OrderSupplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OrderSupplier();
        $model->order_kode = 'PO_'.date('d');
        $model->status = 'ordered';
        $model->user_id = Yii::$app->user->identity->id;
        $model->created = date('Y-m-d H:i:s');
        $post_data = Yii::$app->request->post();

        $transaction = OrderSupplier::getDb()->beginTransaction(); 
        if ($model->load($post_data) && $model->save()) {
            try{
                if(isset($post_data['barang'])){
                    foreach ($post_data['barang'] as $key => $value) {
                        $model_detail = new OrderSupplierDetail();
                        $barang = explode('|', $value);
                        $model_detail->order_id = $model->order_id;
                        $model_detail->item_cd = $barang[0];
                        $model_detail->satuan_supplier = $barang[1];
                        $model_detail->harga_supplier = $post_data['harga_supplier_baru'][$key];
                        $model_detail->jumlah_order_supplier = $post_data['jumlah_barang'][$key];
                        $model_detail->satuan_stok = $barang[3];
                        $model_detail->jumlah_order_stok = $barang[4]*$post_data['jumlah_barang'][$key];
                        $model_detail->save();
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->order_id]);
            }  catch(\Exception $e) {
                $transaction->rollBack();
                //throw $e;
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error! Jangan Memilih Barang/Pelayanan yang sama!');
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
            
            
        } else {
            $model->order_tanggal = date('Y-m-d');
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing OrderSupplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $item_exist = OrderSupplierDetail::find()->where(['order_id'=>$id])->asArray()->all();
        $model->user_id = Yii::$app->user->identity->id;
        $post_data = Yii::$app->request->post();
        $transaction = OrderSupplier::getDb()->beginTransaction(); 
        if ($model->load($post_data) && $model->save()) {
            try{
                OrderSupplierDetail::deleteAll(["order_id"=>$model->order_id]);
                if(isset($post_data['barang'])){
                    foreach ($post_data['barang'] as $key => $value) {
                        $model_detail = new OrderSupplierDetail();
                        $barang = explode('|', $value);
                        $model_detail->order_id = $model->order_id;
                        $model_detail->item_cd = $barang[0];
                        $model_detail->satuan_supplier = $barang[1];
                        $model_detail->harga_supplier = $barang[2];
                        $model_detail->jumlah_order_supplier = $post_data['jumlah_barang'][$key];
                        $model_detail->satuan_stok = $barang[3];
                        $model_detail->jumlah_order_stok = $barang[4]*$post_data['jumlah_barang'][$key];
                        $model_detail->save();
                    }
                }
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->order_id]);
            }  catch(\Exception $e) {
                $transaction->rollBack();
                //throw $e;
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error! Jangan Memilih Barang/Pelayanan yang sama!');
                return $this->render('update', [
                    'model' => $model,
                    'item_exist' => $item_exist,
                ]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
                'item_exist' => $item_exist
            ]);
        }
    }

    /**
     * Deletes an existing OrderSupplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        OrderSupplierDetail::deleteAll(["order_id"=>$id]);
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    public function actionApprove($id)
    {
        $role = (string)Yii::$app->user->identity->role;
        $model = $this->findModel($id);
        $model->status = 'approved';
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionDisapprove($id)
    {
        $role = (string)Yii::$app->user->identity->role;
        if ($role[0]=='2')
            throw new ForbiddenHttpException("Forbidden access");

        $post_data = Yii::$app->request->post('OrderSupplier');
        if (isset($post_data['catatan']))
        {
            $model = $this->findModel($id);
            $model->status = 'disapprove';
            $model->save();
            return $this->redirect(['index']);
        }

        $item_exist = OrderSupplierDetail::find()
            ->joinWith('barang')
            ->where(['order_id'=>$id])->asArray()->all();
        return $this->render('disapprove', [
            'model' => $this->findModel($id),
            'item_exist'=>$item_exist
        ]);
    }


    public function actionCancelApprove($id)
    {
        $role = (string)Yii::$app->user->identity->role;
        if ($role[0]=='2')
            throw new ForbiddenHttpException("Forbidden access");

        $model = $this->findModel($id);
        $model->status = 'ordered';
        $model->catatan = '';
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionCancelDisapprove($id)
    {
        $role = (string)Yii::$app->user->identity->role;
        if ($role[0]=='2')
            throw new ForbiddenHttpException("Forbidden access");

        $model = $this->findModel($id);
        $model->status = 'ordered';
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionReceive($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'terima';
        $item_exist = OrderSupplierDetail::find()->joinWith('barang')->where(['order_id'=>$id])->asArray()->all();
        $model->user_id = Yii::$app->user->identity->id;
        $model->status = 'received';
        $model->total_harga=0;
        $post_data = Yii::$app->request->post();
        $transaction = OrderSupplier::getDb()->beginTransaction(); 

        if ($model->load($post_data)) {

            $totalAll = 0;
            foreach ($post_data['harga_baru'] as $key => $value) 
                $totalAll += ($value * $post_data['jumlah_receive'][$key]);
            $model->total_harga = $model->total_harga>0 ? $model->total_harga + $totalAll : $totalAll;
            $model->save();
            
            try{
                $model_stok = new InvItemMove();
                if(isset($post_data['jumlah_receive'])){
                    foreach ($post_data['jumlah_receive'] as $barang_id => $value) {
                        $model_detail = OrderSupplierDetail::findOne(['order_id'=>$model->order_id,'item_cd'=>$barang_id]);
                        $model_detail->jumlah_receive_supplier = $model_detail->jumlah_receive_supplier > 0 ? $model_detail->jumlah_receive_supplier+$value : $value;
                        $model_detail->expired_date = $post_data['expired_date'][$barang_id];
                        $model_detail->kondisi_receive = $post_data['kondisi_receive'][$barang_id];
                        $model_detail->harga_supplier = $post_data['harga_baru'][$barang_id];
                        $model_detail->jumlah_receive_stok = $model_detail->jumlah_receive_supplier * ($model_detail->jumlah_order_stok/$model_detail->jumlah_order_supplier);
                        $terima_saat_ini = $value * ($model_detail->jumlah_order_stok/$model_detail->jumlah_order_supplier);
                        
                        if($model_detail->save()){
                            $model_stok->tambahStok(
                                $barang_id,
                                $terima_saat_ini,
                                $model->order_id,
                                $model->supplier_cd,
                                $model_detail->harga_supplier,
                                $model_detail->expired_date
                            );
                            
                        }
                    }
                }
                // echo '<pre>';
                // print_r($post_data);
                // die;
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->order_id]);
            }  catch(\Exception $e) {
                $transaction->rollBack();
                //throw $e;
                \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');
                return $this->render('receive', [
                    'model' => $model,
                    'item_exist' => $item_exist,
                ]);
            }
            
        } else {
            return $this->render('receive', [
                'model' => $model,
                'item_exist' => $item_exist
            ]);
        }
    }

    public function actionCancelReceive($id)
    {
        $role = (string)Yii::$app->user->identity->role;
        if ($role[0]=='2')
            throw new ForbiddenHttpException("Forbidden access");

        $StokBarang = new StokBarang();
        $transaction = OrderSupplier::getDb()->beginTransaction(); 
        try{
            $model = $this->findModel($id);
            $model->user_id = Yii::$app->user->identity->id;
            $model->status = 'approved';
            $model->save();
            $item_exist = OrderSupplierDetail::find()->where(['order_id'=>$id])->asArray()->all();
            foreach ($item_exist as $key => $value) {
                $StokBarang->kurangiStok(
                    $value['barang_id'],
                    $model->cabang_id,
                    $value['jumlah_receive_stok'],
                    'receive_supplier_cancel',
                    $model->order_id
                );
                $model_detail = OrderSupplierDetail::findOne(['order_id'=>$model->order_id,'barang_id'=>$value['barang_id']]);
                $model_detail->jumlah_receive_supplier = 0;
                $model_detail->kondisi_receive = "";
                $model_detail->save();
            }
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            //throw $e;
            \Yii::$app->getSession()->setFlash('error', 'Terdapat Error!');            
        }
        
        return $this->redirect(['view', 'id' => $model->order_id]);
    }

    public function actionLaporanOrder($cat=1)
    {
        if ($post = Yii::$app->request->post())
        {
            if ($post['tipe']=='Cetak Laporan')
                $this->redirect(['cetak', 'cat'=>$cat,'PO'=>$post['PO'],'stat'=>$post['stat']]);
            if (($post['tipe']=='Download Laporan') && ($post['cat']==1))
                $this->redirect(['export-alat', 'PO'=>$post['PO'],'stat'=>$post['stat']]);
            if (($post['tipe']=='Download Laporan') && ($post['cat']==2))
                $this->redirect(['export-bahan', 'PO'=>$post['PO'],'stat'=>$post['stat']]);
        }
        $list = ArrayHelper::map(OrderSupplier::find()->distinct()->all(), 'order_kode', 'order_kode');
        return $this->render('laporanMenu',['list'=>$list, 'cat'=>$cat]);
    }

    public function actionCetak($PO, $cat=1, $stat = 'received')
    {
        $lab = ($stat == 'received')? "Lab. Pengguna": 'Lab. Pengusul';

        $rows = OrderSupplier::getDataReport($stat, $PO, $cat);
        if ($cat==1)
            $header = ['No.', 'Nama Barang', 'Spesifikasi', 'Jumlah', 'Harga per item', 'Harga total', $lab, 'Catatan'];
        else if ($cat==2) 
            $header = ['No.', 'Nama Barang', 'Spesifikasi', 'Jumlah', 'Harga per item', 'Harga total', $lab, 'Catatan'];

        if ($stat == 'approved')
        {
            $header[] = 'Supplier Pemenang';
        }

        return $this->renderPartial('cetak', ['rows'=>$rows, 'HEADER'=>$header, 'cat'=>$cat, 'stat'=>$stat]);
    }

    function cell($baris,$kolom)
    {   
        return (chr(64+$kolom).$baris);
    }

    function styleBorder()
    {
        return array(
              'borders' => array(
                'allborders' => array(
                  'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
              )
            );
    }

    public function actionExportAlat($stat = 'received', $PO = 'PO_13')
    {
        $rows = OrderSupplier::getDataReport($stat, $PO, 1);

        // echo '<pre>';
        // print_r($rows);
        // die;

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $creator = "RSUD Berau";
        $mod = "RSUD Berau";
        $title = "Laporan Order Alat";

        

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator($creator)
                             ->setLastModifiedBy($mod)
                             ->setTitle($title)
                             ->setSubject($title)
                             ->setDescription($title)
                             ->setKeywords($title)
                             ->setCategory($title);

        // $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
        // $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        // $objDrawingPType->setName("Pareto By Type");
        // $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
        // $objDrawingPType->setCoordinates('C2');
        // $objDrawingPType->setOffsetX(100);
        // $objDrawingPType->setWidthAndHeight(100,100);

        // $objPHPExcel->getActiveSheet()->setTitle($title)
        //         ->setCellValue($this->cell(2,4), 'Lampiran SPK')
        //         ->setCellValue($this->cell(3,4), 'Pengadaan Alat Laboratorium Tahun Akademik')
        //         ->setCellValue($this->cell(4,4), 'Universitas Muhammadiyah Purwokerto');
        // $baris = 8;
        // $kolom = 2;
        // $lKolom = ($stat == 'received')? 8:9;


        //STATUS ORDERED
//STATUS ORDER
        if ($stat=='ordered')
        {
            $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(100);
            $objDrawingPType->setWidthAndHeight(100,100);

            $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell(2,3), 'RSUD Berau')
                    ->setCellValue($this->cell(3,3), 'kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182')
                    ->setCellValue($this->cell(4,4), 'Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239')
                    ->setCellValue($this->cell(5,3), 'kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181')
                    ->setCellValue($this->cell(6,4), 'Telp. (0281) 6844252, 6844253, Fax. (0281) 67239')
                    ->setCellValue($this->cell(8,3), 'DAFTAR USULAN BAHAN LABORATORIUM');
            
            $objPHPExcel->getActiveSheet()->getStyle('C2:C2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C8:C8')->getFont()->setBold(true);

            $baris = 10;
            $barisbegin = $baris;
            $kolom = 2;
            $lKolom = 8;
        }              
        elseif ($stat=='approved')
        {
            $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(100);
            $objDrawingPType->setWidthAndHeight(100,100);

            $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell(2,3), 'RSUD Berau')
                    ->setCellValue($this->cell(3,3), 'kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182')
                    ->setCellValue($this->cell(4,4), 'Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239')
                    ->setCellValue($this->cell(5,3), 'kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181')
                    ->setCellValue($this->cell(6,4), 'Telp. (0281) 6844252, 6844253, Fax. (0281) 67239')
                    ->setCellValue($this->cell(8,3), 'LAMPIRAN SPK')
                    ->setCellValue($this->cell(9,3), 'Nomor : ...........');
            
            $objPHPExcel->getActiveSheet()->getStyle('C2:C2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C8:C8')->getFont()->setBold(true);

            $baris = 11;
            $barisbegin = $baris;
            $kolom = 2;
            $lKolom = 9;
        }        
        elseif ($stat=='received')
        {
            $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(100);
            $objDrawingPType->setWidthAndHeight(100,100);

            $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell(2,3), 'RSUD Berau')
                    ->setCellValue($this->cell(3,3), 'kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182')
                    ->setCellValue($this->cell(4,4), 'Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239')
                    ->setCellValue($this->cell(5,3), 'kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181')
                    ->setCellValue($this->cell(6,4), 'Telp. (0281) 6844252, 6844253, Fax. (0281) 67239')
                    ->setCellValue($this->cell(8,3), 'BERITA ACARA PENYERAHAN BARANG')
                    ->setCellValue($this->cell(9,3), 'Nomor : ...........')
                    ->setCellValue($this->cell(11,1), 'Pada hari .............. Di purwokerto, sesuai dengan surat No......... Tanggal......')
                    ->setCellValue($this->cell(12,1), 'Telah terjadi penyerahan/ penerimaan barang anara :')
                    ->setCellValue($this->cell(14,2), 'Nama :')
                    ->setCellValue($this->cell(15,2), 'Jabatan : Pelaksanaan Bagian Inventaris')
                    ->setCellValue($this->cell(16,2), 'Alamat : Kampus I UMP Dukuhwaluh, Purwokerto')
                    ->setCellValue($this->cell(17,2), 'Sebagai Pihak yang menyerahkan barang')
                    ->setCellValue($this->cell(19,2), 'Nama :')
                    ->setCellValue($this->cell(20,2), 'Jabatan : Kepala Laboratorium .....')
                    ->setCellValue($this->cell(21,2), 'Alamat : Kampus I UMP Dukuhwaluh, Purwokerto')
                    ->setCellValue($this->cell(22,2), 'Sebagai Pihak yang menerima barang');
            
            $objPHPExcel->getActiveSheet()->getStyle('C2:C2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C8:C8')->getFont()->setBold(true);

            $baris = 24;
            $barisbegin = $baris;
            $kolom = 2;
            $lKolom = 8;
        }             

        $lab = ($stat == 'received')? "Lab. Pengguna": 'Lab. Pengusul';

        $objPHPExcel->getActiveSheet()->setTitle($title)
                ->setCellValue($this->cell($baris,1), 'No.')
                ->setCellValue($this->cell($baris,2), 'Nama Barang')
                ->setCellValue($this->cell($baris,3), 'Spesifikasi')
                ->setCellValue($this->cell($baris,4), 'Jumlah')
                ->setCellValue($this->cell($baris,5), 'Harga per item')
                ->setCellValue($this->cell($baris,6), 'Harga Total')
                ->setCellValue($this->cell($baris,7), $lab)
                ->setCellValue($this->cell($baris,8), 'Catatan');

        if ($stat == 'approved')
            $objPHPExcel->getActiveSheet()->setTitle($title)
                ->setCellValue($this->cell($baris,9), 'Supplier Pemenang');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


        for ($i=0; $i <count($rows) ; $i++) 
        { 
            $baris++;
            $objPHPExcel->getActiveSheet()->setTitle($title)
                ->setCellValue($this->cell($baris,1), $rows[$i][0])
                ->setCellValue($this->cell($baris,2), $rows[$i][1])
                ->setCellValue($this->cell($baris,3), $rows[$i][2])
                ->setCellValue($this->cell($baris,4), $rows[$i][3])
                ->setCellValue($this->cell($baris,5), $rows[$i][4])
                ->setCellValue($this->cell($baris,6), $rows[$i][5])
                ->setCellValue($this->cell($baris,7), $rows[$i][6])
                ->setCellValue($this->cell($baris,8), $rows[$i][7]);

            if ($stat == 'approved')
                $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell($baris,9), $rows[$i][8]);

        }

        $objPHPExcel->getActiveSheet()->getStyle(($this->cell($barisbegin,1).':'.$this->cell($baris,$lKolom)))->applyFromArray($this->styleBorder());

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$PO."_ALAT_".date("d-m-Y-His").'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    public function actionExportBahan($stat = 'received', $PO = 'PO_13')
    {
        $rows = OrderSupplier::getDataReport($stat, $PO, 2);
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $creator = "Ryan Arief Misnadin";
        $mod = "Universitas Muhammadiyah Purwokerto";
        $title = "Laporan Order Alat";

        $objPHPExcel = new \PHPExcel();

        // $objPHPExcel->getProperties()->setCreator($creator)
        //                      ->setLastModifiedBy($mod)
        //                      ->setTitle($title)
        //                      ->setSubject($title)
        //                      ->setDescription($title)
        //                      ->setKeywords($title)
        //                      ->setCategory($title);

        // $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
        // $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        // $objDrawingPType->setName("Pareto By Type");
        // $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
        // $objDrawingPType->setCoordinates('C2');
        // $objDrawingPType->setOffsetX(100);
        // $objDrawingPType->setWidthAndHeight(100,100);

        // $objPHPExcel->getActiveSheet()->setTitle($title)
        //         ->setCellValue($this->cell(2,4), 'Lampiran SPK')
        //         ->setCellValue($this->cell(3,4), 'Pengadaan Alat Laboratorium Tahun Akademik')
        //         ->setCellValue($this->cell(4,4), 'Universitas Muhammadiyah Purwokerto');

        //STATUS ORDER
        if ($stat=='ordered')
        {
            $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(100);
            $objDrawingPType->setWidthAndHeight(100,100);

            $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell(2,3), 'RSUD Berau')
                    ->setCellValue($this->cell(3,3), 'kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182')
                    ->setCellValue($this->cell(4,4), 'Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239')
                    ->setCellValue($this->cell(5,3), 'kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181')
                    ->setCellValue($this->cell(6,4), 'Telp. (0281) 6844252, 6844253, Fax. (0281) 67239')
                    ->setCellValue($this->cell(8,3), 'DAFTAR USULAN BAHAN LABORATORIUM');
            
            $objPHPExcel->getActiveSheet()->getStyle('C2:C2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C8:C8')->getFont()->setBold(true);

            $baris = 10;
            $barisbegin = $baris;
            $kolom = 2;
            $lKolom = 8;
        }              
        elseif ($stat=='approved')
        {
            $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(100);
            $objDrawingPType->setWidthAndHeight(100,100);

            $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell(2,3), 'RSUD Berau')
                    ->setCellValue($this->cell(3,3), 'kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182')
                    ->setCellValue($this->cell(4,4), 'Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239')
                    ->setCellValue($this->cell(5,3), 'kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181')
                    ->setCellValue($this->cell(6,4), 'Telp. (0281) 6844252, 6844253, Fax. (0281) 67239')
                    ->setCellValue($this->cell(8,3), 'LAMPIRAN SPK')
                    ->setCellValue($this->cell(9,3), 'Nomor : ...........');
            
            $objPHPExcel->getActiveSheet()->getStyle('C2:C2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C8:C8')->getFont()->setBold(true);

            $baris = 11;
            $barisbegin = $baris;
            $kolom = 2;
            $lKolom = 9;
        }        
        elseif ($stat=='received')
        {
            $objDrawingPType = new \PHPExcel_Worksheet_Drawing();
            $objDrawingPType->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
            $objDrawingPType->setName("Pareto By Type");
            $objDrawingPType->setPath(\Yii::getAlias('@webroot')."/adminlte/img/logo.png");
            $objDrawingPType->setCoordinates('B2');
            $objDrawingPType->setOffsetX(100);
            $objDrawingPType->setWidthAndHeight(100,100);

            $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell(2,3), 'RSUD Berau')
                    ->setCellValue($this->cell(3,3), 'kampus I: Jl. Raya Dukuhwaluh PO.BOX 2020 Purwokerto 53182')
                    ->setCellValue($this->cell(4,4), 'Telp. (0281) 636751, 630463, 634424 Fax. (0281) 67239')
                    ->setCellValue($this->cell(5,3), 'kampus I: Jl. Letjen Soepardjo Rosetam Km. 7 PO.BOX 299 Sokoraja Purwokerto 53181')
                    ->setCellValue($this->cell(6,4), 'Telp. (0281) 6844252, 6844253, Fax. (0281) 67239')
                    ->setCellValue($this->cell(8,3), 'BERITA ACARA PENYERAHAN BARANG')
                    ->setCellValue($this->cell(9,3), 'Nomor : ...........')
                    ->setCellValue($this->cell(11,1), 'Pada hari .............. Di purwokerto, sesuai dengan surat No......... Tanggal......')
                    ->setCellValue($this->cell(12,1), 'Telah terjadi penyerahan/ penerimaan barang anara :')
                    ->setCellValue($this->cell(14,2), 'Nama :')
                    ->setCellValue($this->cell(15,2), 'Jabatan : Pelaksanaan Bagian Inventaris')
                    ->setCellValue($this->cell(16,2), 'Alamat : Kampus I UMP Dukuhwaluh, Purwokerto')
                    ->setCellValue($this->cell(17,2), 'Sebagai Pihak yang menyerahkan barang')
                    ->setCellValue($this->cell(19,2), 'Nama :')
                    ->setCellValue($this->cell(20,2), 'Jabatan : Kepala Laboratorium .....')
                    ->setCellValue($this->cell(21,2), 'Alamat : Kampus I UMP Dukuhwaluh, Purwokerto')
                    ->setCellValue($this->cell(22,2), 'Sebagai Pihak yang menerima barang');
            
            $objPHPExcel->getActiveSheet()->getStyle('C2:C2')->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->getStyle('C8:C8')->getFont()->setBold(true);

            $baris = 24;
            $barisbegin = $baris;
            $kolom = 2;
            $lKolom = 8;
        }        

        $lab = ($stat == 'received')? "Lab. Pengguna": 'Lab. Pengusul';

        $objPHPExcel->getActiveSheet()->setTitle($title)
                ->setCellValue($this->cell($baris,1), 'No.')
                ->setCellValue($this->cell($baris,2), 'Nama Barang')
                ->setCellValue($this->cell($baris,3), 'Spesifikasi')
                ->setCellValue($this->cell($baris,4), 'Jumlah')
                ->setCellValue($this->cell($baris,5), 'Harga per item')
                ->setCellValue($this->cell($baris,6), 'Harga Total')
                ->setCellValue($this->cell($baris,7), $lab)
                ->setCellValue($this->cell($baris,8), 'Catatan');

        if ($stat == 'approved')
            $objPHPExcel->getActiveSheet()->setTitle($title)
                ->setCellValue($this->cell($baris,9), 'Supplier Pemenang');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);


        for ($i=0; $i <count($rows) ; $i++) 
        { 
            $baris++;
            $objPHPExcel->getActiveSheet()->setTitle($title)
                ->setCellValue($this->cell($baris,1), $rows[$i][0])
                ->setCellValue($this->cell($baris,2), $rows[$i][1])
                ->setCellValue($this->cell($baris,3), $rows[$i][2])
                ->setCellValue($this->cell($baris,4), $rows[$i][3])
                ->setCellValue($this->cell($baris,5), $rows[$i][4])
                ->setCellValue($this->cell($baris,6), $rows[$i][5])
                ->setCellValue($this->cell($baris,7), $rows[$i][6])
                ->setCellValue($this->cell($baris,8), $rows[$i][7]);

            if ($stat == 'approved')
                $objPHPExcel->getActiveSheet()->setTitle($title)
                    ->setCellValue($this->cell($baris,9), $rows[$i][8]);

        }

        $objPHPExcel->getActiveSheet()->getStyle(($this->cell($barisbegin,1).':'.$this->cell($baris,$lKolom)))->applyFromArray($this->styleBorder());

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$PO."_BAHAN_".date("d-m-Y-His").'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /**
     * Finds the OrderSupplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderSupplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OrderSupplier::findOne($id)) !== null) {
        $role = (string)Yii::$app->user->identity->role;
        if (($role[0]=='2') && (Yii::$app->user->identity->cabang_id!=$model->cabang_id))
            throw new ForbiddenHttpException("Forbidden access");
        else
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
