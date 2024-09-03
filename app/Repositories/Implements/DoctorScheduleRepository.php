<?php

namespace App\Repositories\Implements;

use App\Models\DoctorSchedule;
use App\Repositories\Interfaces\DoctorScheduleInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DoctorScheduleRepository implements DoctorScheduleInterface
{

    public function getScheduleByPolyAndDay(string $polyCode, int $day)
    {
        return DB::select("
            SELECT SCL.nama,
                   SCL.kode,
                   SCL.kode_poli,
                   ALL_SC.jadwal_praktek
            FROM (
                SELECT SC.kode_poli,
                       SC.hari,
                       DR.nama,
                       DR.kode
                FROM jadwal_dokters SC
                JOIN dokters DR ON DR.kode = SC.kode_dokter
                WHERE SC.kode_poli = :kode_poli
                  AND SC.hari = :hari
                GROUP BY DR.nama, DR.kode, DR.nama, SC.kode_poli, SC.hari
            ) AS SCL
            LEFT JOIN (
                SELECT kode,
                       CONCAT('[', GROUP_CONCAT(sch.jadwal_praktek SEPARATOR ','), ']') AS jadwal_praktek
                FROM (
                    SELECT kode
                    FROM dokters
                ) AS dr
                LEFT JOIN (
                    SELECT kode_dokter,
                           CONCAT(
                               JSON_OBJECT(
                                   'hari_praktek', hari,
                                   'jam_praktek_buka', jam_praktek_buka,
                                   'jam_praktek_tutup', jam_praktek_tutup
                               )
                           ) AS jadwal_praktek
                    FROM jadwal_dokters
                    GROUP BY hari, jam_praktek_buka, jam_praktek_tutup, kode_dokter
                    ORDER BY hari DESC
                ) AS sch ON dr.kode = sch.kode_dokter
                GROUP BY dr.kode
            ) AS ALL_SC ON SCL.kode = ALL_SC.kode
            GROUP BY SCL.nama, SCL.kode, SCL.kode_poli, ALL_SC.jadwal_praktek
        ", [
            'kode_poli' => $polyCode,
            'hari' => $day,
        ]);
    }

    public function getCurrentScheduleWithQuota(
        string $doctorCode, int $day, string $checkupDate,string $openPracticeHour,string $closePracticeHour): array
    {
        return DB::select("
            SELECT
                SCL.kapasitas_pasien,
                TQ.total_antrian,
                SCL.jam_praktek_buka,
                SCL.jam_praktek_tutup
            FROM (
                SELECT
                    SC.kapasitas_pasien_bpjs,
                    SC.kapasitas_pasien_non_bpjs,
                    SC.kapasitas_pasien,
                    SC.jam_praktek_buka,
                    SC.jam_praktek_tutup,
                    SC.kode_dokter
                FROM jadwal_dokters SC
                WHERE SC.kode_dokter = '$doctorCode'
                    AND SC.hari = '$day'
                    AND SC.jam_praktek_buka = '$openPracticeHour'
                    AND SC.jam_praktek_tutup = '$closePracticeHour'
            ) SCL
            LEFT JOIN (
                SELECT
                    TQ.kode_dokter,
                    IFNULL(COUNT(*), 0) AS total_antrian
                FROM antrian_polis TQ
                WHERE TQ.tanggal_periksa = '$checkupDate'
                  AND TQ.status_pelayanan IN (0, 1, 2, 3)
                  AND TQ.jam_praktek_buka = '$openPracticeHour'
                  AND TQ.jam_praktek_tutup = '$closePracticeHour'
                GROUP BY TQ.kode_dokter
            ) TQ ON SCL.kode_dokter = TQ.kode_dokter
        ");
    }

    public function getDoctorScheduleWithQuota(string $checkupDate,  $day, string $polyCode): array
    {
        return DB::select("
            SELECT
                dr.nama,
                dr.kode,
                dr.kode_poli,
                dscl.hari,
                dscl.jam_praktek_buka,
                dscl.jam_praktek_tutup,
                dscl.kapasitas_pasien,
                dscl.libur,
                COALESCE(ds.total_antrian, 0) AS total_antrian
            FROM (
                     SELECT
                         sc.kode_poli,
                         sc.hari,
                         dr.nama,
                         dr.kode
                     FROM jadwal_dokters sc
                              JOIN dokters dr ON dr.kode = sc.kode_dokter
                     WHERE sc.kode_poli = '$polyCode'
                       AND sc.hari = '$day'
                     GROUP BY dr.nama, dr.kode, sc.kode_poli, sc.hari
                 ) AS dr
                     LEFT JOIN (
                SELECT
                    scl.kode_dokter,
                    scl.hari,
                    scl.libur,
                    scl.jam_praktek_buka,
                    scl.jam_praktek_tutup,
                    scl.kapasitas_pasien
                FROM jadwal_dokters scl
                WHERE scl.hari = '$day'
                  AND scl.kode_poli = '$polyCode'
            ) AS dscl ON dscl.kode_dokter = dr.kode
                     LEFT JOIN (
                SELECT
                    dq.kode_dokter,
                    dq.jam_praktek_buka,
                    dq.jam_praktek_tutup,
                    COUNT(*) AS total_antrian
                FROM antrian_polis dq
                WHERE dq.tanggal_periksa = '$checkupDate'
                  AND dq.status_pelayanan IN (0, 1, 2, 3)
                GROUP BY dq.kode_dokter, dq.jam_praktek_buka, dq.jam_praktek_tutup
            ) AS ds ON ds.kode_dokter = dscl.kode_dokter
                AND ds.jam_praktek_buka = dscl.jam_praktek_buka
                AND ds.jam_praktek_tutup = dscl.jam_praktek_tutup;
        ");
    }
    public function getCurrentDoctorPractice(string $doctorCode, string $practiceStartHour, string $practiceEndHour, int $day)
    {
        $result = DB::select("
            SELECT jadwal_dokters.*, dokters.nama
            FROM jadwal_dokters
            JOIN dokters ON jadwal_dokters.kode_dokter = dokters.kode
            WHERE jadwal_dokters.kode_dokter = ?
            AND jadwal_dokters.hari = ?
            AND jadwal_dokters.jam_praktek_buka = ?
            AND jadwal_dokters.jam_praktek_tutup = ?
            LIMIT 1
        ", [$doctorCode, $day, $practiceStartHour, $practiceEndHour]);

        return $result ? $result[0] : null;
    }

    public function getScheduleById($id)
    {
        return DoctorSchedule::find($id);
    }

    public function updateSchedule(DoctorSchedule | Builder $schedule, array $data): bool|int
    {
       return $schedule->update($data);
    }

    public function massDeleteByDoctorCode(string $doctorCode)
    {
        return DoctorSchedule::where('kode_dokter', $doctorCode)
            ->delete();
    }

    public function getScheduleByDoctorCode(string $doctorCode)
    {
        return DoctorSchedule::where('kode_dokter', $doctorCode);
    }

    public function createSchedule(array $schedule)
    {
        return DoctorSchedule::create($schedule);
    }

}
