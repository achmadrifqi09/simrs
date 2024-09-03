<?php

namespace App\Observers;

use App\Models\AdmissionQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdmissionQueueObserver
{
    /**
     * Handle the AdmissionQueue "created" event.
     */
    public function creating(AdmissionQueue $admissionQueue): void
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $admissionQueue->created_by = Auth::id();
        $admissionQueue->updated_by = Auth::id();
        $admissionQueue->created_at = $currentTime;
        $admissionQueue->deleted_by = 0;
        $admissionQueue->restored_by = 0;
    }

    /**
     * Handle the AdmissionQueue "updated" event.
     */
    public function updating(AdmissionQueue $admissionQueue): void
    {
        $admissionQueue->updated_by = Auth::id();
    }

    /**
     * Handle the AdmissionQueue "deleted" event.
     */
    public function deleting(AdmissionQueue $admissionQueue): void
    {
        $admissionQueue->deleted_by = Auth::id();
    }

    /**
     * Handle the AdmissionQueue "restored" event.
     */
    public function restoring(AdmissionQueue $admissionQueue): void
    {
        $admissionQueue->restored_by = Auth::id();
    }

    /**
     * Handle the AdmissionQueue "force deleted" event.
     */
    public function forceDeleted(AdmissionQueue $admissionQueue): void
    {
        //
    }
}
