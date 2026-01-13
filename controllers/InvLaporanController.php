<?php

namespace app\controllers;

use Yii;
use app\models\InvItemMaster;
use app\models\InvBatchItem;
use app\models\Inventory;
use app\models\search\InvItemMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvItemMasterController implements the CRUD actions for InvItemMaster model.
 */
class InvLaporanController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all InvSupplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInv()
    {
        $model = new Inventory();
        if(!empty(Yii::$app->request->post())){
            $post_data = Yii::$app->request->post();
            $data = $model->getHasilLaporan(
                $post_data['jenis_laporan'],$post_data['start_date'],$post_data['end_date']
            );
            
            return $this->render($post_data['jenis_laporan'],compact('data','post_data'));
        }
        return $this->render('inventory',[
            'jenis_laporan' => $model->jenis_laporan
        ]);
    }

    
    public function actionCetak()
    {
        $post = Yii::$app->request->post();
        //var_dump($post);exit;
        $tanggal_awal = $post['tanggal_awal'];
        $tanggal_akhir = $post['tanggal_akhir'];
        $jenis_laporan = $post['jenis_laporan'];
        //var_dump($jenis_laporan);
        if($jenis_laporan == '1'){        
        $data = InvBatchItem::findBySql("select @no:=@no+1 `No`, a.* from
                        (select
                        supplier_nm Supplier, 
                        order_tanggal Tanggal, 
                        order_kode `No. Dok.`, 
                        item_nm `Nama Barang`, 
                        satuan_stok `Satuan`, 
                        jumlah_order_supplier Jumlah, 
                        harga_supplier Harga, 
                        jumlah_order_supplier * harga_supplier `Harga Total`
                        from 
                        inv_order_supplier join 
                        inv_order_supplier_detail using(order_id) join
                        inv_supplier using(supplier_cd) join
                        inv_item_master using(item_cd)
                        WHERE 
                        order_tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                        order by supplier_nm, order_tanggal) a,
                        (select @no:=0)b
                        ")->asArray()->all();


        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [

                'Order Supplier' => [   // Name of the excel sheet
                    'data' => $data,

                    // Set to `false` to suppress the title row
                    'titles' => [
                        'No',
                        'Supplier',
                        'Tanggal',
                        'No. Dok.',
                        'Nama Barang',
                        'Satuan',
                        'Jumlah',
                        'Harga Satuan',
                        'Harga Total',
                    ],

                    'formats' => [
                        // Either column name or 0-based column index can be used
                        //'C' => '#,##0.00',
                        //3 => 'dd/mm/yyyy hh:mm:ss',
                    ],

                    'formatters' => [
                        // Dates and datetimes must be converted to Excel format
                        // 3 => function ($value, $row, $data) {
                        //     return \PHPExcel_Shared_Date::PHPToExcel(strtotime($value));
                        // },
                    ],
                ],

            ]
        ]);
        $file->send("Order Supplier.xlsx");   
        }            
    }

    public function actionOrdersupplier($tahun = null, $bulan = null)
    {
        $tahun = empty($tahun) ? date('Y') : $tahun;
        $bulan = empty($bulan) ? date('m') : $bulan;

        $data = InvBatchItem::findBySql("select
                        @no:=@no+1 `No`,
                        supplier_nm,
                        DATE(inv_batch_item.modi_datetime) `Tanggal`,
                        batch_no `No Dok.`,
                        item_nm `Nama Barang`,
                        unit_nm `Satuan`,
                        inv_batch_item.trx_qty `Jumlah`,
                        inv_batch_item.buy_price `Harga Satuan`,
                        (inv_batch_item.buy_price * inv_batch_item.trx_qty) `Harga Total`
                        from inv_batch_item
                        join inv_item_master USING(item_cd)
                        join inv_unit USING(unit_cd)
                        join inv_supplier USING(supplier_cd),
                        (select @no:=0)a
                        WHERE 
                        YEAR(inv_batch_item.modi_datetime) = '$tahun' AND 
                        MONTH(inv_batch_item.modi_datetime) = '$bulan'")->asArray()->all();


        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [

                'Order Supplier' => [   // Name of the excel sheet
                    'data' => $data,

                    // Set to `false` to suppress the title row
                    'titles' => [
                        'No',
                        'Supplier',
                        'No Dok.',
                        'Nama Barang',
                        'Satuan',
                        'Jumlah',
                        'Harga Satuan',
                        'Harga Total',
                    ],

                    'formats' => [
                        // Either column name or 0-based column index can be used
                        'C' => '#,##0.00',
                        3 => 'dd/mm/yyyy hh:mm:ss',
                    ],

                    'formatters' => [
                        // Dates and datetimes must be converted to Excel format
                        3 => function ($value, $row, $data) {
                            return \PHPExcel_Shared_Date::PHPToExcel(strtotime($value));
                        },
                    ],
                ],

            ]
        ]);
        $file->send("Order Supplier $tahun $bulan.xlsx");        
    }

    /**
     * Displays a single InvItemMaster model.
     * @param string $id
     * @return mixed
     */
    
    /**
     * Finds the InvItemMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return InvItemMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvItemMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
