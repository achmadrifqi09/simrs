<?php

namespace App\Livewire\Queue\Addition;

use App\Models\BPJSQueueResponse;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class QueueMonitoringTable extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(BPJSQueueResponse::query())
            ->columns([
                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                TextColumn::make('bpjs_number')
                    ->label('NO BPJS')
                    ->searchable(),
                TextColumn::make('response_status')
                    ->label('Status Terakhir')
                    ->searchable(),
                IconColumn::make('task_id_1')
                    ->label('Task ID 1')
                    ->boolean(),
                IconColumn::make('task_id_2')
                    ->label('Task ID 2')
                    ->boolean(),
                IconColumn::make('task_id_3')
                    ->label('Task ID 3')
                    ->boolean(),
                IconColumn::make('task_id_4')
                    ->label('Task ID 4')
                    ->boolean(),
                IconColumn::make('task_id_5')
                    ->label('Task ID 5')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Aktifitas Terkahir')
                    ->searchable()
                    ->since(),
            ])->actions([
                DeleteAction::make()
                    ->modalHeading("Hapus Log Antrean")
                    ->icon('')
                    ->label('Delete')
            ])->bulkActions([
                ExportBulkAction::make()
                    ->label('Download Excel')
            ]) ->paginated([5, 10, 25, 40]);
    }

    public function render() : View
    {
        return view('livewire.queue.addition.queue-monitoring-table');
    }
}
