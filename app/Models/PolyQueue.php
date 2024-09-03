<?php

namespace App\Models;

use App\Observers\PolyQueueObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
#[ObservedBy(PolyQueueObserver::class)]
class PolyQueue extends Model
{
    use HasFactory;

    protected $table = 'antrian_polis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nomor_kartu',
        'nomor_hp',
        'kode_poli',
        'nomor_rm',
        'hari',
        'tanggal_periksa',
        'kode_dokter',
        'jam_praktek_buka',
        'jam_praktek_tutup',
        'antrian_admisi_id',
        'jenis_kunjungan',
        'nomor_referensi',
        'nomor_antrian',
        'kode_antrian',
        'kode_booking',
        'keterangan_batal',
        'status_pelayanan',
        'ambil_dari',
    ];

    public function updatedByUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function createdByUser() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
