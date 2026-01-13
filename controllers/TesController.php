<?php

namespace app\controllers;

use Yii;
use app\models\Kelas;
use app\models\search\KelasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\bridging\Eklaim;

/**
 * KelasController implements the CRUD actions for Kelas model.
 */
class TesController extends Controller
{
    public function actionIndex()
    {
        $r = new Eklaim(['method'=>"new_claim"], [
                'nomor_kartu'=>"0000668870001",
                'nomor_sep'=>"1710R01011160001249",
                'nomor_rm'=>'000117',
                'nama_pasien'=>'NAMA TEST PASIEN',
                'tgl_lahir'=>'1940-01-01 02:00:00',
                'gender'=>'2',
            ]);
        echo '<pre>';
        print_r($r->execute());
    }
}
