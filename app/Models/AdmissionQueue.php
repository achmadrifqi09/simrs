<?php

namespace App\Models;

use App\Observers\AdmissionQueueObserver;
use App\Traits\CanGetTableNameStatically;
use App\Traits\UserStamp;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(AdmissionQueueObserver::class)]
class AdmissionQueue extends Model
{
    use HasFactory;
    protected $table = 'antrian_admisis';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nomor_antrian', 'loket_antrian_id', 'kode_booking', 'status_pelayanan', 'kode_antrian'
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
