<?php

namespace App\Livewire\Doctor;

use App\Livewire\Forms\Doctor\DoctorForm;
use App\Models\Doctor as DoctorModel;
use App\Models\Doctor;
use App\Services\DoctorScheduleService;
use App\Services\DoctorService;
use App\Services\PolyclinicService;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class DoctorTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public bool $doctorModal = false;
    public string $doctorModalTitle = "Tambah Dokter Baru";
    public DoctorForm $form;
    protected DoctorService $doctorService;
    protected DoctorScheduleService $doctorScheduleService;
    protected PolyclinicService $polyclinicService;

    public function boot(DoctorService $doctorService, DoctorScheduleService $doctorScheduleService, PolyclinicService $polyclinicService): void
    {
        $this->doctorService = $doctorService;
        $this->doctorScheduleService = $doctorScheduleService;
        $this->polyclinicService = $polyclinicService;
    }


    public function render(): View
    {

        return view('livewire.doctor.doctor-table');
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(
                DoctorModel::query()
                    ->with(['schedules' => function ($query) {
                        $query->select(['kode_dokter', 'kode_poli'])
                            ->with(['polyclinic' => function ($query) {
                                $query->select(['nama', 'kode']);
                            }])
                            ->groupBy('kode_dokter', 'hari', 'kode_poli');
                    }])
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Dokter')
                    ->searchable(),
                TextColumn::make('schedules.polyclinic.nama')
                    ->label('Poliklinik')
                    ->searchable(),
                TextColumn::make('kode')
                    ->label('Kode Dokter')
                    ->searchable(),
            ])
            ->actions([
                Action::make('detail')
                    ->label('Jadwal')
                    ->action(function ($record) {
                        $this->dispatch('to-schedule', id: $record->id);
                    }),
                Action::make('doctor-edit')
                    ->label('Edit Dokter')
                    ->action(function ($record) {
                        $this->doctorModal = true;
                        $this->doctorModalTitle = 'Edit Dokter ';
                        $this->form->fill([
                            'name' => $record->nama,
                            'code' => $record->kode,
                        ]);
                        $this->form->doctor = $record;
                    }),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Dokter')
                    ->action(function (Doctor $record) {
                        $doctorCode = $record->kode;
                        $record->delete();
                        $this->doctorScheduleService->massDeleteByDoctorCode($doctorCode);
                        $this->dispatch('notification', ['message' => "Berhasil menghapus data dokter"]);
                    })
            ])
            ->bulkActions([
                ExportBulkAction::make()
                    ->label('Download Excel')
            ])
            ->paginated([5, 10, 25, 35])
            ->defaultPaginationPageOption(5);
    }


    public function submit()
    {
        $this->form->validate();
        if ($this->form?->doctor) {
            $this->doctorService
                ->update($this->form->doctor->id, $this->form->doctor->kode, [
                    'name' => $this->form->name,
                    'code' => $this->form->code,
                ]);
            $this->dispatch('notification', ['message' => 'Berhasil memperbarui data dokter']);
        } else {
            $this->doctorService
                ->create([
                    'name' => $this->form->name,
                    'code' => $this->form->code,
                ]);
            $this->dispatch('notification', ['message' => 'Berhasil menambahkan data dokter']);
        }
        $this->doctorModal = false;
        $this->form->reset();
    }

    public function handleModalCreate()
    {
        $this->doctorModal = true;
        $this->resetErrorBag();
        $this->form->reset();
        $this->doctorModalTitle = "Tambah Dokter Baru";
    }

    #[On('to-schedule')]
    public function toDetail($id): void
    {
        $this->redirect('/referensi/dokter/' . $id . '/jadwal', navigate: true);
    }

}
