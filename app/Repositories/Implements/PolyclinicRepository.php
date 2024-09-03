<?php

namespace App\Repositories\Implements;

use App\Models\Polyclinic;
use App\Repositories\Interfaces\PolyclinicInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PolyclinicRepository implements PolyclinicInterface
{
    public function getAllPolyclinics() : Collection
    {
       return Polyclinic::where('status', 1)->get();
    }

    public function updateOrCreate($data)
    {
        return Polyclinic::updateOrCreate(
            ['id' => $data->polyclinic->id ?? 0],
            [
            'kode' => $data->code,
            'nama' => $data->name,
            'status' => $data->status,
            ]
        );
    }

    public function getPolyclinicByCode($polyCode) : Polyclinic
    {
        return Polyclinic::where('status', 1)
        ->where('kode', $polyCode)
            ->first();
    }

    public function getPolyclinicByName(string $name)
    {
        return Polyclinic::where('status', 1)
        ->where('nama', 'like', '%'.$name.'%')
            ->get();
    }

    public function getPolyclinicWithSchedule(): array
    {
       return DB::select("
        SELECT
            poly.nama,
            poly.kode,
            CONCAT(
                '[',
                GROUP_CONCAT(
                    JSON_OBJECT(
                        'nama_dokter',
                        dokter.nama,
                        'kode_dokter',
                        dokter.kode,
                        'jadwal',
                        dokter.jadwal
                    ) SEPARATOR ','
                ),
                ']'
            ) AS dokter
        FROM
            polis poly
        LEFT JOIN(
            SELECT d.kode,
                d.nama,
                j.kode_poli,
                CONCAT(
                    '[',
                    GROUP_CONCAT(
                        JSON_OBJECT(
                            'hari',
                            j.hari,
                            'jam_praktek',
                            j.jam_praktek
                        ) SEPARATOR ','
                    ),
                    ']'
                ) AS jadwal
            FROM
                dokters d
            LEFT JOIN(
                SELECT kode_dokter,
                    kode_poli,
                    hari,
                    CONCAT(
                        jam_praktek_buka,
                        '-',
                        jam_praktek_tutup
                    ) AS jam_praktek
                FROM
                    jadwal_dokters
                GROUP BY
                    kode_dokter,
                    hari,
                    jam_praktek_buka,
                    jam_praktek_tutup,
                    kode_poli
            ) j
        ON
            d.kode = j.kode_dokter
        GROUP BY
            d.kode,
            d.nama,
            j.kode_poli
        ) dokter
        ON
            dokter.kode_poli = poly.kode
        WHERE
            poly.status = 1
        GROUP BY
            poly.nama,
            poly.kode;
        ");
    }

    public function searchPolyclinic(string $keyword)
    {
        return Polyclinic::where('nama', 'like', '%'.$keyword.'%')
            ->orWhere('kode', 'like', '%'.$keyword.'%')
            ->get();
    }
}
