<?php

namespace App\Models;

use App\Observers\DoctorScheduleObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy(DoctorScheduleObserver::class)]
class DoctorSchedule extends Model
{
    use HasFactory;
    protected $table = 'jadwal_dokters';
    protected $primaryKey = "id";
    protected $fillable = [
        'hari',
        'kapasitas_pasien',
        'kapasitas_pasien_bpjs',
        'kapasitas_pasien_non_bpjs',
        'libur',
        'jam_praktek_buka',
        'jam_praktek_tutup',
        'kode_poli',
        'kode_dokter'
    ];

    public function doctor() : BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'kode_dokter', 'kode');
    }

    public function polyclinic():BelongsTo
    {
        return $this->belongsTo(Polyclinic::class, 'kode_poli', 'kode');
    }
}
