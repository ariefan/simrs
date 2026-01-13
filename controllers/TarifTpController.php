<?php

namespace app\controllers;

use Yii;
use app\models\TarifTp;
use app\models\TarifTpItem;
use app\models\TarifGeneral;
use app\models\TarifParamedis;
use app\models\TarifUnitmedis;
use app\models\TarifKelas;
use app\models\TarifTindakan;
use app\models\TarifInventori;
use app\models\search\TarifTpSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TarifTpController implements the CRUD actions for TarifTp model.
 */
class TarifTpController extends Controller
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




    public function actionList($q=null, $jt=null, $id=null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if((!is_null($q))&&(!is_null($jt))){
            $query = "select * from(
                select tarif_general_id as id, concat(TG.tarif_nm, ' >> ', K.kelas_nm, ' >> ', I.insurance_nm) as `text` from tarif_general TG, asuransi I, kelas K WHERE TG.insurance_cd = I.insurance_cd AND TG.kelas_cd = K.kelas_cd
                UNION
                select tarif_paramedis_id as id, CONCAT(TP.paramedis_tp, ' >> ', K.kelas_nm,' >> ', I.insurance_nm) as `text` from tarif_paramedis TP, kelas K, asuransi I WHERE TP.kelas_cd = K.kelas_cd  and TP.insurance_cd=I.insurance_cd
                UNION
                select TU.tarif_unitmedis_id as id, CONCAT(UMI.medicalunit_nm, UM.medunit_nm, ' >> ', (SELECT kelas.kelas_nm from kelas WHERE kelas.kelas_cd=TU.kelas_cd), ' >> ', (SELECT insurance_nm from asuransi WHERE asuransi.insurance_cd=TU.insurance_cd)) as `text` from tarif_unitmedis TU, unit_medis_item UMI, unit_medis UM WHERE TU.medicalunit_cd=UMI.medicalunit_cd and UMI.medunit_cd = UM.medunit_cd
                UNION
                select TK.seq_no as id, CONCAT(K.kelas_nm,' >> ',I.insurance_nm)as `text` from tarif_kelas TK, kelas K, asuransi I where TK.kelas_cd = K.kelas_cd and TK.insurance_cd=I.insurance_cd
                UNION 
                SELECT TT.tarif_tindakan_id as id, CONCAT(T.nama_tindakan,' >> ', K.kelas_nm, ' >> ', I.insurance_nm) from tarif_tindakan TT, tindakan T, kelas K, asuransi I where TT.treatment_cd = T.tindakan_cd and TT.kelas_cd=K.kelas_cd and TT.insurance_cd=I.insurance_cd
                UNION
                SELECT TI.seq_no as id, CONCAT(IM.item_nm, ' >> ', IM.unit_cd, ' >> ', K.kelas_nm, ' >> ', I.insurance_nm) from tarif_inventori TI,inv_item_master IM, asuransi I, kelas K where TI.insurance_cd=I.insurance_cd and TI.kelas_cd=K.kelas_cd and TI.item_cd=IM.item_cd
            ) as tarif where tarif.`text` like '%{$q}%' and id like '{$jt}%'";

            $connection = \Yii::$app->db;
            $model = $connection->createCommand($query);
            $data = $model->queryAll();
        }
        $out['results'] = array_values($data);
        return $out;
    }


    /**
     * Lists all TarifTp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TarifTpSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TarifTp model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TarifTp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TarifTp();

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('TarifTp');
            $transaction = Yii::$app->db->beginTransaction();
            $model->save();

            $totalTP = 0;

            foreach ($post['tariftp'] as $key => $value) {
                $tpItem = new TarifTpItem;
                $tpItem->tariftp_no = $model->tariftp_no;
                $tpItem->tarif_tp = $value;
                $tpItem->tarif_item = $post['tarifSatuan'][$key];
                $tpItem->quantity = $post['jumlah'][$key];
                $tpItem->modi_datetime = date('Y-m-d H:i:s');
                $tpItem->modi_id = Yii::$app->user->identity->username;
                $tpItem->save();
                $totalTP += ($tpItem->tarif_item*$tpItem->quantity);
            }

            if ($post['tarif_tp']=='G')
                $tarifInduk = TarifGeneral::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='P')
                $tarifInduk = TarifParamedis::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='U')
                $tarifInduk = TarifUnitmedis::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='K')
                $tarifInduk = TarifKelas::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='T')
                $tarifInduk = TarifTindakan::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='I')
                $tarifInduk = TarifInventori::findOne($model->trx_tarif_seqno);

            if ($totalTP!=$tarifInduk->tarif)
            {
                $transaction->rollback();
                \Yii::$app->getSession()->setFlash('error', 'Total detail tarif tidak sesuai dengan tarif induk');
                // echo $totalTP.' '.$tarifInduk->tarif;
                return $this->redirect(['tarif-tp/create']);
            }

            $model->tarif_total = $totalTP;
            $model->modi_datetime = date('Y-m-d H:i:s');
            $model->modi_id = Yii::$app->user->identity->username;

            if ($model->save())
            {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->tariftp_no]);
            }

        }
        return $this->render('create', [
            'model' => $model,
        ]);
    
    }

    /**
     * Updates an existing TarifTp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post('TarifTp');
            $transaction = Yii::$app->db->beginTransaction();
            $model->save();

            $totalTP = 0;
            //hapus tarif_tp_item lama
            TarifTpItem::deleteAll(['tariftp_no'=>$model->tariftp_no]);

            foreach ($post['tariftp'] as $key => $value) {
                $tpItem = new TarifTpItem;
                $tpItem->tariftp_no = $model->tariftp_no;
                $tpItem->tarif_tp = $value;
                $tpItem->tarif_item = $post['tarifSatuan'][$key];
                $tpItem->quantity = $post['jumlah'][$key];
                $tpItem->modi_datetime = date('Y-m-d H:i:s');
                $tpItem->modi_id = Yii::$app->user->identity->username;
                $tpItem->save();
                $totalTP += ($tpItem->tarif_item*$tpItem->quantity);
            }

            if ($post['tarif_tp']=='G')
                $tarifInduk = TarifGeneral::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='P')
                $tarifInduk = TarifParamedis::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='U')
                $tarifInduk = TarifUnitmedis::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='K')
                $tarifInduk = TarifKelas::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='T')
                $tarifInduk = TarifTindakan::findOne($model->trx_tarif_seqno);
            elseif ($post['tarif_tp']=='I')
                $tarifInduk = TarifInventori::findOne($model->trx_tarif_seqno);

            if ($totalTP!=$tarifInduk->tarif)
            {
                $transaction->rollback();
                \Yii::$app->getSession()->setFlash('error', 'Total detail tarif tidak sesuai dengan tarif induk');
                // echo $totalTP.' '.$tarifInduk->tarif;
                return $this->refresh();
            }

            $model->tarif_total = $totalTP;

            $model->modi_datetime = date('Y-m-d H:i:s');
            $model->modi_id = Yii::$app->user->identity->username;
            if ($model->save())
            {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->tariftp_no]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TarifTp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TarifTp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TarifTp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TarifTp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
