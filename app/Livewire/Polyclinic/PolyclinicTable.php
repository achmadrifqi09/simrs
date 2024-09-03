<?php

namespace App\Livewire\Polyclinic;

use App\Livewire\Forms\Polyclinic\PolyclinicForm;
use App\Models\Polyclinic;
use App\Services\PolyclinicService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class PolyclinicTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $label = "Poliklinik";
    public bool $polyclinicModal = false;
    public string $polyclinicModalTitle = 'Tambah Poliklinik';
    public PolyclinicForm $form;
    private PolyclinicService $polyclinicService;

    public function boot(PolyclinicService $polyclinicService): void
    {
        $this->polyclinicService = $polyclinicService;
    }

    public function render(): View
    {
        return view('livewire.polyclinic.polyclinic-table');
    }

    public function openCreateModal(): void
    {
        $this->polyclinicModal = true;
        $this->polyclinicModalTitle = 'Tambah Poliklinik';
        $this->form->reset();
        $this->resetErrorBag();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Polyclinic::query())
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Poliklinik')
                    ->searchable(),
                TextColumn::make('kode')
                    ->label('Kode Poliklinik')
                    ->searchable(),
                ToggleColumn::make('status')
                    ->label('Visibilitas')
            ])->actions([
                Action::make('polyclinic-edit')
                    ->label('Edit')
                    ->action(function ($record) {
                        $this->polyclinicModal = true;
                        $this->polyclinicModalTitle = 'Edit Poliklinik ' . $record->nama;
                        $this->form->fill([
                            'name' => $record->nama,
                            'code' => $record->kode,
                            'status' => $record->status
                        ]);
                        $this->resetErrorBag();
                        $this->form->polyclinic = $record;
                    }),
                DeleteAction::make()
                    ->label('Delete')
                    ->icon('')
                    ->modalHeading('Hapus Poliklinik')
                    ->successNotification(function () {
                        $this->dispatch('notification', ['message' => "Berhasil menghapus poliklinik"]);
                    }),
            ])->bulkActions([
                ExportBulkAction::make()
                    ->label('Download Excel')
            ]);
    }

    public function submit(): void
    {
        $this->form->validate();
        $this->polyclinicService->updateOrCreatePoly($this->form);
        $this->form->reset();
        $this->polyclinicModal = false;
        $this->dispatch('notification', ['message' => "Berhasil melakukan aksi"]);

    }

    public function getPluralModelLabel(): string
    {
        return "asdf";
    }

}
