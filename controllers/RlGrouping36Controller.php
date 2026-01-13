<?php

namespace app\controllers;

use Yii;
use app\models\RlRef36;
use app\models\RlGrouping36;
use app\models\Tindakan;
use app\models\RlGrouping36Search;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RlGrouping36Controller implements the CRUD actions for RlGrouping36 model.
 */
class RlGrouping36Controller extends Controller
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
     * Lists all RlGrouping36 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data = RlRef36::find()->all();
        return $this->render('index',compact('data'));
    }

    /**
     * Displays a single RlGrouping36 model.
     * @param integer $rl_ref_36_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionView($rl_ref_36_no, $tindakan_cd)
    {
        return $this->render('view', [
            'model' => $this->findModel($rl_ref_36_no, $tindakan_cd),
        ]);
    }

    /**
     * Creates a new RlGrouping36 model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RlGrouping36();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'rl_ref_36_no' => $model->rl_ref_36_no, 'tindakan_cd' => $model->tindakan_cd]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RlGrouping36 model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $rl_ref_36_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = RlRef36::findOne($id);
        $tindakan = Tindakan::getDataTindakan();
        $grouping = RlGrouping36::find()->where(['rl_ref_36_no'=>$id])->all();
        $jenis_exist = []; $tindakan_exist = [];
        foreach ($grouping as $key => $value) {
            $jenis_exist[$value['tindakan_cd']] = $value['jenis'];
            $tindakan_exist[$value['tindakan_cd']] = 1;
        }

        if (!empty(Yii::$app->request->post())) {
            $post_data = Yii::$app->request->post();
            RlGrouping36::deleteAll(['rl_ref_36_no'=>$id]);
            foreach ($post_data['tindakan'] as $cd => $nm) {
                $m = new RlGrouping36();
                $m->rl_ref_36_no = $id;
                $m->tindakan_cd = (string) $cd;
                $m->jenis = $post_data['jenis'][$cd];
                $m->save();
            }
            \Yii::$app->getSession()->setFlash('success', 'Berhasil Menyimpan Data');
            return $this->redirect('index');
        } else {
            return $this->render('update', compact('tindakan','grouping','model','jenis_exist','tindakan_exist'));
        }
    }

    /**
     * Deletes an existing RlGrouping36 model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $rl_ref_36_no
     * @param string $tindakan_cd
     * @return mixed
     */
    public function actionDelete($rl_ref_36_no, $tindakan_cd)
    {
        $this->findModel($rl_ref_36_no, $tindakan_cd)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RlGrouping36 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $rl_ref_36_no
     * @param string $tindakan_cd
     * @return RlGrouping36 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($rl_ref_36_no, $tindakan_cd)
    {
        if (($model = RlGrouping36::findOne(['rl_ref_36_no' => $rl_ref_36_no, 'tindakan_cd' => $tindakan_cd])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
