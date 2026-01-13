<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RL MILIK RSUD WONOSARI
 */
class Rl extends Model
{
    public $tahun;
    public $jenis_laporan = [
        "rl_12" => "RL 1.2 - Indikator Pelayanan",
        "rl_13" => "RL 1.3 - Tempat Tidur",
        // "rl_2" => "RL 2 - Ketenagaan",
        "rl_31" => "RL 3.1 - Rawat Inap",
        "rl_32" => "RL 3.2 - Rawat Darurat",
        "rl_33" => "RL 3.3 - Gigi Mulut",
        "rl_34" => "RL 3.4 - Kebidanan",
        "rl_35" => "RL 3.5 - Perinatologi",
        "rl_36" => "RL 3.6 - Pembedahan",
        "rl_37" => "RL 3.7 - Radiologi",
        "rl_38" => "RL 3.8 - Laboratorium",
        "rl_39" => "RL 3.9 - Rehab Medik",
        "rl_310" => "RL 3.10 - Pelayanan Khusus",
        "rl_311" => "RL 3.11 - Kesehatan Jiwa",
        "rl_312" => "RL 3.12 - Keluaraga Berencana",
        "rl_313" => "RL 3.13 - Obat",
        "rl_314" => "RL 3.14 - Rujukan",
        "rl_315" => "RL 3.15 - Cara Bayar",
        "rl_4a" => "RL 4a - Penyakit Rawat Inap (Gabungan)",
        "rl_4a_p" => "RL 4a - Penyakit Rawat Inap (Primer)",
        "rl_4a_s" => "RL 4a - Penyakit Rawat Inap (Sekunder)",
        "rl_4b" => "RL 4b - Penyakit Rawat Jalan (Gabungan)",
        "rl_4b_p" => "RL 4b - Penyakit Rawat Jalan (Primer)",
        "rl_4b_s" => "RL 4b - Penyakit Rawat Jalan (Sekunder)",
        "rl_51" => "RL 5.1 - Pengunjung",
        "rl_52" => "RL 5.2 - Kunjungan Rawat Jalan",
        "rl_53" => "RL 5.3 - 10 Besar Penyakit Rawat Inap",
        "rl_54" => "RL 5.4 - 10 Besar Penyakit Rawat Jalan",
    ];

    public function getHasilLaporan($report_name,$start_date,$end_date){        
        if($report_name=="rl_12"){
            $sql = $this->sqlRL12($start_date,$end_date);
        } elseif($report_name=="rl_13"){
            $sql = $this->sqlRL13($start_date,$end_date);
        } elseif($report_name=="rl_31"){
            $sql = $this->sqlRL31($start_date,$end_date);
        }elseif($report_name=="rl_32"){
            $sql = $this->sqlRL32($start_date,$end_date);
        }elseif($report_name=="rl_33"){
            $sql = $this->sqlRL33($start_date,$end_date);
        }elseif($report_name=="rl_34"){
            $sql = $this->sqlRL34($start_date,$end_date);
        }elseif($report_name=="rl_35"){
            $sql = $this->sqlRL35($start_date,$end_date);
        }elseif($report_name=="rl_36"){
            $sql = $this->sqlRL36($start_date,$end_date);
        }elseif($report_name=="rl_37"){
            $sql = $this->sqlRL37($start_date,$end_date);
        } elseif($report_name=="rl_4b"){
            $sql = $this->sqlRl4B($start_date,$end_date);
        } elseif($report_name=="rl_4b_p"){
            $sql = $this->sqlRl4B($start_date,$end_date,"RM_TP_1");
        } elseif($report_name=="rl_4b_s"){
            $sql = $this->sqlRl4B($start_date,$end_date,"RM_TP_2");
        } elseif($report_name=="rl_4a"){
            $sql = $this->sqlRl4A($start_date,$end_date);
        } elseif($report_name=="rl_4a_b"){
            $sql = $this->sqlRl4A($start_date,$end_date,"RM_TP_1");
        } elseif($report_name=="rl_4a_s"){
            $sql = $this->sqlRl4A($start_date,$end_date,"RM_TP_2");
        } elseif($report_name=="rl_51"){
            $sql = $this->sqlRL51($start_date,$end_date);
        } elseif($report_name=="rl_52"){
            $sql = $this->sqlRL52($start_date,$end_date);
        } elseif($report_name=="rl_53"){
            $sql = $this->sqlRL53($start_date,$end_date);
        } elseif($report_name=="rl_54"){
            $sql = $this->sqlRL54($start_date,$end_date);
        }


        $connection = Yii::$app->db;
        $command = $connection->createCommand($sql);     
        return $command->queryAll();
    }

    public function sqlRL12($start_date,$end_date){
        return "SELECT 
                  bangsal_cd AS `Bangsal`,
                  SUM(
                    IF(
                      DATEDIFF(jam_selesai, jam_masuk) = 0,
                      1,
                      DATEDIFF(jam_selesai, jam_masuk)
                    )
                  ) / (
                    (SELECT 
                      COUNT(1) 
                    FROM
                      ruang) * DATEDIFF('$start_date', '$end_date')
                  ) AS BOR,
                  SUM(
                    IF(
                      DATEDIFF(jam_selesai, jam_masuk) = 0,
                      1,
                      DATEDIFF(jam_selesai, jam_masuk)
                    )
                  ) / SUM(IF(jam_selesai IS NOT NULL, 1, 0)) AS AVLOS,
                  (
                    (
                      (SELECT 
                        COUNT(1) 
                      FROM
                        ruang) * DATEDIFF('$start_date', '$end_date')
                    ) - COUNT(1)
                  ) / SUM(IF(jam_selesai IS NOT NULL, 1, 0)) AS TOI,
                  (SUM(IF(jam_selesai IS NOT NULL, 1, 0))) / 
                  (SELECT 
                    COUNT(1) 
                  FROM
                    ruang) AS BTO,
                  SUM(IF(jenis_keluar = 3, 1, 0)) / SUM(IF(jam_selesai IS NOT NULL, 1, 0)) * 1000 AS NDR,
                  SUM(IF(jenis_keluar IN (2, 3), 1, 0)) / SUM(IF(jam_selesai IS NOT NULL, 1, 0)) * 1000 AS GDR 
                FROM
                  kunjungan 
                  JOIN ruang USING (ruang_cd) 
                WHERE tipe_kunjungan = 'Rawat Inap' 
                  AND tanggal_periksa BETWEEN '$start_date' 
                  AND '$end_date' 
                GROUP BY bangsal_cd ;

                ";
    }

    public function sqlRL13($start_date,$end_date){
        return "SELECT 
              bangsal_nm AS `Bangsal`,
              kelas_nm AS `Kelas`,
              COUNT(1) AS `Jumlah TT`
            FROM
              ruang 
              JOIN bangsal USING (bangsal_cd) 
              JOIN kelas USING (kelas_cd) 
            GROUP BY bangsal_cd,kelas_cd ";
    }

    public function sqlRL32($start_date,$end_date){
        /*
         * KODE BEDAH S , T
         * KEBIDANAN O , N70 - N77 , N80 - N98
         * PSIKIATRIK F
         * ANAK 0 - 17 TAHUN
         * NON BEDAH , DILUAR DIATAS , INCLUDE ANAK
         */
        return "SELECT
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.reff_tp IS NOT NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.reff_tp IS NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH NON RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.out_tp = 'OUT_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH DIRAWAT',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.out_tp = 'OUT_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH DIRUJUK',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.out_tp = 'OUT_TP_03' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH PULANG PAKSA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.out_tp = 'OUT_TP_04' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH MATI DI UGD',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND medical.out_tp = 'OUT_TP_05' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH DOA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'S%'
                            OR medical_record.icd_cd LIKE 'T%'
                        )
                        AND (medical.out_tp IS NULL OR medical.out_tp = '') THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'BEDAH TANPA KETERANGAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.reff_tp IS NOT NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.reff_tp IS NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN NON RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.out_tp = 'OUT_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN DIRAWAT',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.out_tp = 'OUT_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN DIRUJUK',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.out_tp = 'OUT_TP_03' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN PULANG PAKSA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.out_tp = 'OUT_TP_04' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN MATI DI UGD',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND medical.out_tp = 'OUT_TP_05' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN DOA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd LIKE 'O%'
                            OR icd_root BETWEEN 'N70'
                            AND 'N77'
                            OR icd_root BETWEEN 'N80'
                            AND 'N98'
                        )
                        AND (medical.out_tp IS NULL OR medical.out_tp = '') THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'KEBIDANAN TANPA KETERANGAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.reff_tp IS NOT NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.reff_tp IS NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK NON RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.out_tp = 'OUT_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK DIRAWAT',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.out_tp = 'OUT_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK DIRUJUK',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.out_tp = 'OUT_TP_03' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK PULANG PAKSA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.out_tp = 'OUT_TP_04' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK MATI DI UGD',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND medical.out_tp = 'OUT_TP_05' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK DOA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND DATEDIFF(
                                YEAR,
                                pasien.birth_date,
                                medical.datetime_in
                            )BETWEEN 0
                            AND 17
                        )
                        AND (medical.out_tp IS NULL OR medical.out_tp = '') THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'ANAK TANPA KETERANGAN',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.reff_tp IS NOT NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK RUJUKAN',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.reff_tp IS NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK NON RUJUKAN',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.out_tp = 'OUT_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK DIRAWAT',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.out_tp = 'OUT_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK DIRUJUK',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.out_tp = 'OUT_TP_03' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK PULANG PAKSA',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.out_tp = 'OUT_TP_04' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK MATI DI UGD',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND medical.out_tp = 'OUT_TP_05' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK DOA',
                    SUM(
                        CASE
                        WHEN medical_record.icd_cd LIKE 'F%'
                        AND (medical.out_tp IS NULL OR medical.out_tp = '') THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'PSIKIATRIK TANPA KETERANGAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.reff_tp IS NOT NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.reff_tp IS NULL THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH NON RUJUKAN',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.out_tp = 'OUT_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH DIRAWAT',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.out_tp = 'OUT_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH DIRUJUK',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.out_tp = 'OUT_TP_03' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH PULANG PAKSA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.out_tp = 'OUT_TP_04' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH MATI DI UGD',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND medical.out_tp = 'OUT_TP_05' THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH DOA',
                    SUM(
                        CASE
                        WHEN(
                            medical_record.icd_cd NOT LIKE 'T%'
                            AND medical_record.icd_cd NOT LIKE 'S%'
                            AND medical_record.icd_cd NOT LIKE 'F%'
                            AND medical_record.icd_cd NOT LIKE 'O%'
                            AND(
                                icd_root NOT BETWEEN 'N70'
                                AND 'N77'
                            )
                            AND(
                                icd_root NOT BETWEEN 'N80'
                                AND 'N98'
                            )
                        )
                        AND (medical.out_tp IS NULL OR medical.out_tp = '') THEN
                            1
                        ELSE
                            0
                        END
                    )AS 'NON BEDAH TANPA KETERANGAN'
                FROM
                    medical
                JOIN medical_record ON medical.medical_cd = medical_record.medical_cd
                JOIN pasien ON medical.pasien_cd = pasien.pasien_cd
                JOIN icd ON medical_record.icd_cd = icd.icd_cd
                WHERE
                    medunit_cd = 'POLIUGD'
                AND datetime_in BETWEEN '$start_date' AND '$end_date';
        ";
    }

    public function sqlRL33($start_date,$end_date){
        return "select TIND.treatment_nm as JENIS_KEGIATAN, COUNT(*) as JUMLAH
                from 
                    medical_tindakan T,
                    medical M,
                    tindakan TIND
                WHERE M.medical_cd = T.medical_cd AND
                    M.datetime_in BETWEEN '$start_date' AND '$end_date' AND
                    M.medunit_cd = 'POLIGIGI' AND
                    T.treatment_cd=TIND.treatment_cd
                GROUP BY TIND.treatment_nm
                ORDER BY COUNT(*) DESC
        ";
    }

    public function sqlRL34($start_date,$end_date){
        return "
            SELECT
                medical_record.icd_cd,
                icd.icd_root,
                medical.reff_tp,
                medical.out_tp,
                COUNT(1)AS TOT
            FROM
                medical
            JOIN medical_record ON medical.medical_cd = medical_record.medical_cd
            JOIN icd ON icd.icd_cd = medical_record.icd_cd
            WHERE
                medical.datetime_in BETWEEN '$start_date'
            AND '$end_date'
            AND medical.medunit_cd = 'POLISPOG'
            GROUP BY
                medical_record.icd_cd,
                icd.icd_root,
                medical.reff_tp,
                medical.out_tp
            ORDER BY icd_cd
        ";
        
    }

    public function sqlRL35($start_date,$end_date){
        //belum
        return "SELECT
                RTRIM(LTRIM(C.treatment_nm)) AS Jenis_Kegiatan,
                COUNT(1)AS Jumlah
            FROM
                simrke.medical_tindakan A
            JOIN simrke.medical B ON A.medical_cd = B.medical_cd
            JOIN tindakan C ON A.treatment_cd = C.treatment_cd
            WHERE
                B.datetime_in BETWEEN '$start_date' AND '$end_date'
            GROUP BY
                C.treatment_nm
        ";
    }

    public function sqlRL36($start_date,$end_date){
        //belum
        return "SELECT DISTINCT UM.medunit_nm,
                    (
                        SELECT COUNT(*)
                        from medical MED,
                            medical_tindakan MT,
                            tindakan T
                        WHERE MED.medunit_cd = UM.medunit_cd AND
                            MED.datetime_in BETWEEN '$start_date' AND '$end_date' AND
                            MT.medical_cd = MED.medical_cd AND
                            MT.treatment_cd = T.treatment_cd AND
                            (T.treatment_tp = 'TREATMENT_TP_2' OR T.treatment_tp = 'TREATMENT_TP_3' OR T.treatment_tp = 'TREATMENT_TP_4' OR T.treatment_tp = 'TREATMENT_TP_5')
                    ) AS TOTAL,
                    (
                        SELECT COUNT(*)
                        from medical MED,
                            medical_tindakan MT,
                            tindakan T
                        WHERE MED.medunit_cd = UM.medunit_cd AND
                            MED.datetime_in BETWEEN '$start_date' AND '$end_date' AND
                            MT.medical_cd = MED.medical_cd AND
                            MT.treatment_cd = T.treatment_cd AND
                            T.treatment_tp = 'TREATMENT_TP_5'
                    ) AS KHUSUS,
                    (
                        SELECT COUNT(*)
                        from medical MED,
                            medical_tindakan MT,
                            tindakan T
                        WHERE MED.medunit_cd = UM.medunit_cd AND
                            MED.datetime_in BETWEEN '$start_date' AND '$end_date' AND
                            MT.medical_cd = MED.medical_cd AND
                            MT.treatment_cd = T.treatment_cd AND
                            T.treatment_tp = 'TREATMENT_TP_4'
                    ) AS BESAR,
                    (
                        SELECT COUNT(*)
                        from medical MED,
                            medical_tindakan MT,
                            tindakan T
                        WHERE MED.medunit_cd = UM.medunit_cd AND
                            MED.datetime_in BETWEEN '$start_date' AND '$end_date' AND
                            MT.medical_cd = MED.medical_cd AND
                            MT.treatment_cd = T.treatment_cd AND
                            T.treatment_tp = 'TREATMENT_TP_3'
                    ) AS SEDANG,
                    (
                        SELECT COUNT(*)
                        from medical MED,
                            medical_tindakan MT,
                            tindakan T
                        WHERE MED.medunit_cd = UM.medunit_cd AND
                            MED.datetime_in BETWEEN '$start_date' AND '$end_date' AND
                            MT.medical_cd = MED.medical_cd AND
                            MT.treatment_cd = T.treatment_cd AND
                            T.treatment_tp = 'TREATMENT_TP_2'
                    ) AS KECIL

                from unit_medis UM
        ";
    }

    public function sqlRL37($start_date,$end_date){
        //belum
        return "SELECT
                RTRIM(LTRIM(C.treatment_nm)) AS Jenis_Kegiatan,
                COUNT(1)AS Jumlah
            FROM
                simrke.medical_tindakan A
            JOIN simrke.medical B ON A.medical_cd = B.medical_cd
            JOIN tindakan C ON A.treatment_cd = C.treatment_cd
            WHERE
                B.datetime_in BETWEEN '$start_date' AND '$end_date'
            GROUP BY
                C.treatment_nm
        ";
    }

    public function sqlRl4A($start_date,$end_date,$tipe="")
    {
        $w = "";
        if(!empty($tipe)){
            $w .= " AND rm_tp = '$tipe'";
        }
        return "SELECT
                    icd.icd_cd,
                    icd.icd_nm,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 0
                        AND 6 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_5,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 0
                        AND 6 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_6,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        ) BETWEEN 6
                        AND 28 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_7,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 6
                        AND 28 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_8,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 28
                        AND 365 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_9,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 28
                        AND 365 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_10,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 1
                        AND 4 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_11,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 1
                        AND 4 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_12,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 4
                        AND 14 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_13,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 4
                        AND 14 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_14,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 14
                        AND 24 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_15,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 14
                        AND 24 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_16,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 24
                        AND 44 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_17,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 24
                        AND 44 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_18,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 44
                        AND 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_19,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 44
                        AND 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_20,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )> 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_21,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )>= 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_22,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_23,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_24,
                    COUNT(1)AS K_25,
                    SUM(
                        CASE
                        WHEN medical.out_tp = 'OUT_TP_04' OR medical.out_tp = 'OUT_TP_05' OR medical.out_tp = 'OUT_TP_07' THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_26
                FROM
                    icd
                LEFT JOIN medical_record ON icd.icd_cd = medical_record.icd_cd
                LEFT JOIN medical ON medical_record.medical_cd = medical.medical_cd
                LEFT JOIN pasien ON medical_record.pasien_cd = pasien.pasien_cd
                WHERE datetime_in BETWEEN '$start_date' AND '$end_date' AND
                    medical.medical_tp = 'MEDICAL_TP_02' $w
                GROUP BY
                    icd.icd_cd,
                    icd.icd_nm";
    }

    public function sqlRl4B($start_date,$end_date,$tipe="")
    {
        $w = "";
        if(!empty($tipe)){
            $w .= " AND rm_tp = '$tipe'";
        }
        return "SELECT
                    icd.icd_cd,
                    icd.icd_nm,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 0
                        AND 6 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_5,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 0
                        AND 6 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_6,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 6
                        AND 28 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_7,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 6
                        AND 28 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_8,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 28
                        AND 365 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_9,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            DAY,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 28
                        AND 365 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_10,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 1
                        AND 4 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_11,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 1
                        AND 4 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_12,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 4
                        AND 14 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_13,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 4
                        AND 14 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_14,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 14
                        AND 24 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_15,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 14
                        AND 24 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_16,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 24
                        AND 44 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_17,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 24
                        AND 44 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_18,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 44
                        AND 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_19,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )BETWEEN 44
                        AND 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_20,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )>= 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_21,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02'
                        AND DATEDIFF(
                            YEAR,
                            pasien.birth_date,
                            CURRENT_TIMESTAMP
                        )>= 64 THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_22,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_01' THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_23,
                    SUM(
                        CASE
                        WHEN pasien.gender_tp = 'GENDER_TP_02' THEN
                            1
                        ELSE
                            0
                        END
                    )AS K_24,
                    COUNT(1)AS K_25,
                    COUNT(1)AS K_26
                FROM
                    icd
                LEFT JOIN medical_record ON icd.icd_cd = medical_record.icd_cd
                LEFT JOIN medical ON medical_record.medical_cd = medical.medical_cd
                LEFT JOIN pasien ON medical_record.pasien_cd = pasien.pasien_cd
                WHERE datetime_in BETWEEN '$start_date' AND '$end_date' AND
                    medical.medical_tp = 'MEDICAL_TP_01' $w
                GROUP BY
                    icd.icd_cd,
                    icd.icd_nm";
    }

    public function sqlRL51($start_date,$end_date)
    {
        /*
         * DIPISAH RAWAT INAP DAN RAWAT JAlAN
         */ 
        return "SELECT
                    SUM(CASE WHEN pasien.register_date < '$start_date' THEN 1 ELSE 0 END) AS jumlah_lama,
                    SUM(CASE WHEN pasien.register_date >= '$start_date' THEN 1 ELSE 0 END) AS jumlah_baru
                FROM
                    medical
                JOIN pasien ON medical.pasien_cd = pasien.pasien_cd
                WHERE datetime_in BETWEEN '$start_date' AND '$end_date'";
    }

    public function sqlRL52($start_date,$end_date){

        return "SELECT
                    unit_medis.medunit_nm,
                    COUNT(1) AS jumlah
                FROM
                    medical
                JOIN unit_medis ON medical.medunit_cd = unit_medis.medunit_cd
                WHERE datetime_in BETWEEN '$start_date' AND '$end_date'
                GROUP BY
                    medunit_nm";
    }

    public function sqlRL53($start_date,$end_date)
    {
        /*
         * JIKA DI KLIK ANGKA-ANGKA NYA MUNCUL DETAIL PASIENNYA
         */
        return "SELECT TOP 50
                    icd.icd_cd,
                    icd.icd_nm,
                    SUM(CASE WHEN pasien.gender_tp = 'GENDER_TP_01' THEN 1 ELSE 0 END) AS jumlah_pria,
                    SUM(CASE WHEN pasien.gender_tp = 'GENDER_TP_02' THEN 1 ELSE 0 END) AS jumlah_wanita,
                    SUM(CASE WHEN medical.out_tp = 'OUT_TP_04' THEN 1 ELSE 0 END) AS jumlah_mati,
                                        SUM(CASE WHEN medical.out_tp <> 'OUT_TP_04' THEN 1 ELSE 0 END) AS jumlah_hidup
                FROM
                    medical_record
                JOIN icd ON medical_record.icd_cd = icd.icd_cd
                JOIN pasien ON pasien.pasien_cd = medical_record.pasien_cd
                JOIN medical ON medical.medical_cd = medical_record.medical_cd
                WHERE
                    datetime_record BETWEEN '$start_date' AND '$end_date'
                AND icd.icd_cd NOT LIKE 'Z%'
                AND medical.medical_tp = 'MEDICAL_TP_02'

                GROUP BY
                    icd.icd_cd,
                    icd.icd_nm
                ORDER BY
                    jumlah_hidup DESC";
    }

    public function sqlRL54($start_date,$end_date)
    {
        /*
         * MASIH KURANG TEPAT, HARUSNYA KASUS BARU SAJA
         * KASUS BARU DITANDAI DENGAN ICD_CD BARU DI PASIEN TERSEBUT 
         * YANG BELUM PERNAH TERJADI SEBELUMNYA (UNTUK PASIEN TERSEBUT)
         */
        return "SELECT TOP 50
                    icd.icd_cd,
                    icd.icd_nm,
                    SUM(CASE WHEN pasien.gender_tp = 'GENDER_TP_01' THEN 1 ELSE 0 END) AS jumlah_pria,
                    SUM(CASE WHEN pasien.gender_tp = 'GENDER_TP_02' THEN 1 ELSE 0 END) AS jumlah_wanita,
                SUM(CASE WHEN pasien.gender_tp = 'GENDER_TP_01' THEN 1 ELSE 0 END)+SUM(CASE WHEN pasien.gender_tp = 'GENDER_TP_02' THEN 1 ELSE 0 END) AS jumlah
                FROM
                    medical_record
                JOIN icd ON medical_record.icd_cd = icd.icd_cd
                JOIN pasien ON pasien.pasien_cd = medical_record.pasien_cd
                JOIN medical ON medical.medical_cd = medical_record.medical_cd
                WHERE
                    datetime_record BETWEEN '$start_date' AND '$end_date'
                AND icd.icd_cd NOT LIKE 'Z%'
                AND medical.medical_tp = 'MEDICAL_TP_01'
                GROUP BY
                    icd.icd_cd,
                    icd.icd_nm
                ORDER BY
                    jumlah DESC";
    }

    
}
