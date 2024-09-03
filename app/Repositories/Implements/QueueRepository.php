<?php

namespace App\Repositories\Implements;

use App\Models\AdmissionQueue;
use App\Models\PolyQueue;
use App\Repositories\Interfaces\QueueInterface;
use Illuminate\Support\Facades\DB;

class QueueRepository implements QueueInterface
{

    public function getQueuePolyclinicSingle($RMCode, $checkupDate, $polyCode, $doctorCode)
    {
        return DB::table(DB::raw("
            SELECT * FROM antrian_polis
            WHERE kode_rm = '$RMCode'
                AND tanggal_periksa = '$checkupDate'
                AND kode_poli = '$polyCode'
                AND kode_dokter = '$doctorCode'
                AND status_pelayanan != '99'
        "))->first();
    }

    public function createAdmissionQueue(array $data)
    {
       return AdmissionQueue::create($data);
    }

    public function createPolyQueue(array $data)
    {
        return PolyQueue::create($data);
    }

    public function currentPolyTotal($checkupDate, $polyCode, $doctorCode, $openPracticeHour)
    {
        $total = DB::select("
           SELECT COUNT(tanggal_periksa) AS total_antrean
            FROM antrian_polis
            WHERE tanggal_periksa LIKE '$checkupDate%'
                AND kode_dokter = '$doctorCode'
                AND jam_praktek_buka = '$openPracticeHour'
                AND deleted_at IS NULL
                AND status_pelayanan != '99'
            "
        );
        return $total[0]->total_antrean ?? 0;
    }

    public function currentAdmissionTotalByCode($currentDate, $queueCode)
    {
        $total  = DB::select("
            SELECT COUNT(created_at) AS total_antrean_admisi
            FROM antrian_admisis
            WHERE created_at LIKE '$currentDate%'
                AND kode_antrian = '$queueCode'
                AND status_pelayanan != '99'
        ");
        return $total[0]->total_antrean_admisi ?? 0;
    }

    public function currentAdmissionTotal($currentDate)
    {
        $total  = DB::select("
            SELECT COUNT(created_at) AS total_antrean_admisi
            FROM antrian_admisis
            WHERE created_at LIKE '$currentDate%'
                AND status_pelayanan != '99'
        ");
        return $total[0]->total_antrean_admisi ?? 0;
    }

    public function JKNQueueTotal($polyCode, $doctorCode, $checkupDate, $openHourPractice)
    {
        $total = DB::select("
           SELECT
                COUNT(*) AS total_antrean_jkn
            FROM antrian_polis
            WHERE kode_poli = '$polyCode'
                AND kode_dokter = '$doctorCode'
                AND tanggal_periksa = '$checkupDate'
                AND jam_praktek_buka = '$openHourPractice'
                AND status_pelayanan != '99'
                AND deleted_at IS NULL
                AND (kode_antrian = 'A' OR kode_antrian = 'B')
        ");

        return $total[0]->total_antrean_jkn  ?? 0;
    }

    public function nonJKNQueueTotal($polyCode, $doctorCode, $checkupDate, $openHourPractice)
    {
        $total = DB::select("
           SELECT
                COUNT(*) AS total_antrean_non_jkn
            FROM antrian_polis
            WHERE kode_poli = '$polyCode'
                AND kode_dokter = '$doctorCode'
                AND tanggal_periksa = '$checkupDate'
                AND jam_praktek_buka = '$openHourPractice'
                AND status_pelayanan != '99'
                AND deleted_at IS NULL
                AND kode_antrian = 'C'
        ");

        return $total[0]->total_antrean_non_jkn  ?? 0;
    }

    public function remainingQueueAdmission($checkupDate, $queueCode, $queueNumber)
    {
        $total = DB::select("
           SELECT COUNT(created_at) AS total_antrean
            FROM antrian_admisis
            WHERE created_at LIKE '$checkupDate%'
                AND kode_antrian = '$queueCode'
                AND waktu_dilayani IS NULL
                AND nomor_antrian < '$queueNumber'
            "
        );
        return $total[0]->total_antrean  ?? 0;
    }

    public function getQueueByRMNumber($checkupDate, $RMNumber)
    {
        $result = DB::select("
            SELECT
                poly.*,
                patient.nama,
                polyclinic.nama  AS nama_poli,
                admission.nomor_antrian AS nomor_antrian_admisi,
                doctor.nama AS nama_dokter
            FROM
                antrian_polis AS poly
                LEFT JOIN antrian_admisis AS admission
                    ON admission.id = poly.antrian_admisi_id
                LEFT JOIN pasiens as patient
                    ON patient.kode_rm = poly.nomor_rm
                LEFT JOIN polis AS polyclinic
                    ON polyclinic.kode = poly.kode_poli
                LEFT JOIN dokters as doctor
                    ON doctor.kode = poly.kode_dokter
            WHERE
                poly.tanggal_periksa = '$checkupDate'
              AND poly.nomor_rm = '$RMNumber'
            LIMIT 1;
        ");
        return $result[0] ?? [];
    }
}
