<?php

namespace App\Repositories\Implements;

use App\Models\Doctor;
use App\Repositories\Interfaces\DoctorInterface;
use Illuminate\Support\Facades\DB;

class DoctorRepository implements DoctorInterface
{
    public function getDoctorById($id): Doctor
    {
        return Doctor::where('id', $id)
            ->with(['schedules' => function ($query) {
                $query->select(['kode_dokter', 'kode_poli'])
                    ->with(['polyclinic' => function ($query) {
                        $query->select(['nama', 'kode']);
                    }])
                    ->groupBy('kode_dokter', 'kode_poli');
            }])
            ->first();
    }

    public function update(Doctor $doctor, $data): Doctor
    {
        $doctor->nama = $data['name'];
        $doctor->kode = $data['code'];
        $doctor->save();
        return $doctor;
    }

    public function create($data): Doctor
    {
        return Doctor::create($data);
    }

    public function getDoctorByPoly($polyCode): array
    {
        return DB::select("
            SELECT
                DR.nama,
                kode_poli,
                kode_dokter
            FROM jadwal_dokters
                     LEFT JOIN (
                SELECT
                    nama,
                    kode
                FROM
                    dokters
            ) DR ON DR.kode = kode_dokter
            WHERE kode_poli = '$polyCode'
            GROUP BY DR.nama, kode_poli, kode_dokter
        ");
    }

    public function getStatistics($day)
    {
        return DB::select("
            SELECT
                (SELECT COUNT(*) FROM dokters) AS total_dokter,
                (SELECT COUNT(DISTINCT kode_dokter) FROM jadwal_dokters WHERE hari = '$day' AND libur = '0') AS total_dokter_praktek,
                (SELECT COUNT(DISTINCT kode_dokter) FROM jadwal_dokters WHERE hari = '$day' AND libur = '1') AS total_dokter_libur
        ")[0];
    }
}
