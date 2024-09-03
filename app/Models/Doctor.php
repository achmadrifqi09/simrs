<?php

namespace App\Models;

use App\Observers\DoctorObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(DoctorObserver::class)]
class Doctor extends Model
{
    use HasFactory;
    protected $table = "dokters";
    protected $primaryKey = "id";

    protected $fillable = ['nama', 'kode'];

    public function schedules() : BelongsTo
    {
        return $this->belongsTo(DoctorSchedule::class, 'kode', 'kode_dokter');
    }
}
