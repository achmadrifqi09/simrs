<?php

namespace App\Observers;

use App\Models\PolyQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PolyQueueObserver
{
    /**
     * Handle the PolyQueue "created" event.
     */
    public function creating(PolyQueue $polyQueue): void
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $polyQueue->created_by = Auth::id();
        $polyQueue->updated_by = Auth::id();
        $polyQueue->created_at = $currentTime;
        $polyQueue->deleted_by = 0;
        $polyQueue->restored_by = 0;
    }

    /**
     * Handle the PolyQueue "updated" event.
     */
    public function updating(PolyQueue $polyQueue): void
    {
        $polyQueue->updated_by = Auth::id();
    }

    /**
     * Handle the PolyQueue "deleted" event.
     */
    public function deleting(PolyQueue $polyQueue): void
    {
        $polyQueue->deleted_by = Auth::id();
    }

    /**
     * Handle the PolyQueue "restored" event.
     */
    public function restoring(PolyQueue $polyQueue): void
    {
        $polyQueue->restored_by = Auth::id();
    }

    /**
     * Handle the PolyQueue "force deleted" event.
     */
    public function forceDeleted(PolyQueue $polyQueue): void
    {
        //
    }
}
