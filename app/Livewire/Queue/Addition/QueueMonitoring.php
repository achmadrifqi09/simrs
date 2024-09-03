<?php

namespace App\Livewire\Queue\Addition;

use App\Models\BPJSQueueResponse;
use App\Models\Polyclinic;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Livewire\Attributes\Title;
use Livewire\Component;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class QueueMonitoring extends Component
{
    public string $currentDate;

    public function mount()
    {
        $this->currentDate = Carbon::now()->format('d-m-Y');
    }


    #[Title('Monitoring Antrean')]
    public function render()
    {
        return view('livewire.queue.addition.queue-monitoring');
    }
}
