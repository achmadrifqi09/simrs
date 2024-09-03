<?php

namespace App\Observers;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function creating(Doctor $doctor): void
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $doctor->created_by = Auth::id();
        $doctor->updated_by = Auth::id();
        $doctor->created_at = $currentTime;
        $doctor->deleted_by = 0;
        $doctor->restored_by = 0;
    }

    /**
     * Handle the Doctor "updated" event.
     */
    public function updating(Doctor $doctor): void
    {
        $doctor->updated_by = Auth::id();
    }

    /**
     * Handle the Doctor "deleted" event.
     */
    public function deleting(Doctor $doctor): void
    {
        $doctor->deleted_by = Auth::id();
    }

    /**
     * Handle the Doctor "restored" event.
     */
    public function restoring(Doctor $doctor): void
    {
        $doctor->restored_by = Auth::id();
    }

    /**
     * Handle the Doctor "force deleted" event.
     */
    public function forceDeleted(Doctor $doctor): void
    {
        //
    }
}
