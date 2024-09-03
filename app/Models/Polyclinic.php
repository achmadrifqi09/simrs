<?php

namespace App\Models;
use App\Services\DoctorService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Polyclinic extends Model
{
    use HasFactory;
    protected $table = 'polis';
    protected $primaryKey = 'id';

    protected $fillable = ['nama', 'kode', 'status'];

    public function schedules() : BelongsTo
    {
       return $this->belongsTo(DoctorSchedule::class, 'kode', 'kode_poli');
    }
}
