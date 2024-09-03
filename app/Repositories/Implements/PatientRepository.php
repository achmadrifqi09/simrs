<?php

namespace App\Repositories\Implements;

use App\Repositories\Interfaces\PatientInterface;
use Illuminate\Support\Facades\DB;

class PatientRepository implements PatientInterface
{

    public function getPatientByNIK($NIKNumber)
    {
        return DB::table(DB::raw("
            (
                SELECT p.*, pg.nomor_kartu
                FROM pasiens p
                LEFT JOIN pasien_penjamins pg ON p.kode_rm = pg.kode_rm
                WHERE p.nik = '$NIKNumber'
                AND pg.deleted_by = 0
            ) AS t
        "))
            ->orderBy('t.kode_rm', 'desc')
            ->first();
    }

    public function getPatientByRM($RMNumber)
    {
        return DB::table(DB::raw("
            (
                SELECT pg.kode_rm, pg.nomor_kartu
                FROM pasien_penjamins pg
                WHERE pg.kode_rm = '$RMNumber'
                AND pg.deleted_by = 0
                GROUP BY pg.kode_rm, pg.nomor_kartu
            ) AS t_pg
        "))->leftJoin(DB::raw("
            (
                SELECT p.*
                FROM pasiens p
            ) AS t_p
        "), 't_pg.kode_rm', '=', 't_p.kode_rm')
            ->orderBy('t_pg.kode_rm', 'desc')
            ->first();
    }

    public function getPatientByBPJSNumber($BPJSNumber)
    {
        return DB::table(DB::raw("
            (
                SELECT pg.kode_rm, pg.nomor_kartu
                FROM pasien_penjamins pg
                WHERE pg.nomor_kartu = '$BPJSNumber'
                AND pg.deleted_by = 0
                GROUP BY pg.kode_rm, pg.nomor_kartu
            ) AS t_pg
        "))->leftJoin(DB::raw("
            (
                SELECT p.*
                FROM pasiens p
            ) AS t_p
        "), 't_pg.kode_rm', '=', 't_p.kode_rm')
            ->orderBy('t_pg.kode_rm', 'desc')
            ->first();
    }
}
