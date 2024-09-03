<?php

namespace App\Observers;

use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleObserver
{
    /**
     * Handle the DoctorSchedule "created" event.
     */
    public function creating(DoctorSchedule $doctorSchedule): void
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $doctorSchedule->created_by = Auth::id();
        $doctorSchedule->updated_by = Auth::id();
        $doctorSchedule->created_at = $currentTime;
        $doctorSchedule->deleted_by = 0;
        $doctorSchedule->restored_by = 0;
    }

    /**
     * Handle the DoctorSchedule "updated" event.
     */
    public function updating(DoctorSchedule $doctorSchedule): void
    {
        $doctorSchedule->updated_by = Auth::id();
    }

    /**
     * Handle the DoctorSchedule "deleted" event.
     */
    public function deleting(DoctorSchedule $doctorSchedule): void
    {
        $doctorSchedule->deleted_by = Auth::id();
    }

    /**
     * Handle the DoctorSchedule "restored" event.
     */
    public function restoring(DoctorSchedule $doctorSchedule): void
    {
        $doctorSchedule->restored_by = Auth::id();
    }

    /**
     * Handle the DoctorSchedule "force deleted" event.
     */
    public function forceDeleted(DoctorSchedule $doctorSchedule): void
    {
        //
    }
}
