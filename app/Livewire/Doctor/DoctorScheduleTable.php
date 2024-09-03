<?php

namespace App\Livewire\Doctor;

use App\Livewire\Forms\DoctorScheduleForm;
use App\Models\DoctorSchedule;
use App\Services\DoctorScheduleService;
use App\Services\PolyclinicService;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

class DoctorScheduleTable extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;


    public object $doctor;
    public DoctorScheduleForm $form;
    public bool $scheduleModal = false;
    protected DoctorScheduleService $doctorScheduleService;
    protected PolyclinicService $polyclinicService;
    public $polyclinics;
    public string $scheduleModalTitle = "Tambah Jadwal Dokter";
    public string $selectedPolyclinic = '';

    public function boot(
        DoctorScheduleService $doctorScheduleService,
        PolyclinicService $polyclinicService): void
    {
        $this->doctorScheduleService = $doctorScheduleService;
        $this->polyclinicService = $polyclinicService;
    }

    public function render() : View
    {
        return view('livewire.doctor.doctor-schedule-table');
    }

    public function handleModal() : void
    {
        $this->scheduleModalTitle ="Tambah Jadwal Dokter";
        $this->resetErrorBag();
        $this->form->reset();
        $this->scheduleModal = !$this->scheduleModal;
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(DoctorSchedule::query()
                ->where('kode_dokter', $this->doctor->kode)
            )
            ->columns([
                TextColumn::make('hari')
                    ->label('Hari Praktek')
                    ->formatStateUsing(fn (string $state): string => $this->getDayName($state))
                    ->searchable(),
                TextColumn::make('kapasitas_pasien')
                    ->label('Kapasitas Pasien')
                    ->searchable(),
                TextColumn::make('kapasitas_pasien_bpjs')
                    ->label('Kapasitas Pasien BPJS')
                    ->searchable(),
                TextColumn::make('kapasitas_pasien_non_bpjs')
                    ->label('Kapasitas Pasien Umum')
                    ->searchable(),
                TextColumn::make('jam_praktek_buka')
                    ->label('Jam Praktek Buka')
                    ->searchable(),
                TextColumn::make('kode_poli')
                    ->label('Kode Poliklinik')
                    ->searchable(),
                IconColumn::make('libur')
                    ->label('Status Libur')
                    ->boolean(),
                TextColumn::make('jam_praktek_tutup')
                    ->label('Jam Praktek Tutup')
                    ->searchable(),
            ])->actions([
                Action::make('doctor-schedule-edit')
                    ->label('Edit')
                    ->action(function($record){
                        $this->resetErrorBag();
                        $this->selectedPolyclinic = $record->kode_poli;
                        $this->form->fill([
                            'day' => $record->hari,
                            'poly_code' => $record->kode_poli,
                            'holiday' => $record->libur,
                            'open_practice_hour' => $record->jam_praktek_buka,
                            'close_practice_hour' => $record->jam_praktek_tutup,
                            'bpjs_capacity' => $record->kapasitas_pasien_bpjs,
                            'non_bpjs_capacity' => $record->kapasitas_pasien_non_bpjs,
                        ]);
                        $this->form->doctorSchedule = $record;
                        $this->scheduleModalTitle = "Edit Jadwal Dokter";
                        $this->scheduleModal = true;
                    }),
                DeleteAction::make('doctor-schedule-delete')
                    ->label('Delete')
            ]);
    }

    public function submit() : void
    {
        $this->form->validate();

        try{
            $submitData = (array) $this->form;
            $submitData['doctor_code'] = $this->doctor->kode;
            if($this->form?->doctorSchedule?->id){
                $this->doctorScheduleService->updateSchedule($submitData, $this->form->doctorSchedule->id);
                $this->dispatch('notification', ['message' => 'Berhasil memperbarui jadwal']);

            }else{
                $submitData['poly_code'] = $this->doctor->poly_code ?? $this->form->poly_code;
                $this->doctorScheduleService->createSchedule($submitData);
                $this->dispatch('notification', ['message' => 'Berhasil menambahkan jadwal']);
            }

            $this->form->reset();
            $this->scheduleModal = false;
        }catch (\Exception $e){
           $this->addError('schedule-action-status', $e->getMessage());
        }
    }

    public function updated() : void
    {
       $this->validateCapacities();
       $this->validatePracticeHours();

       if($this->form->poly_code && $this->selectedPolyclinic !== $this->form->poly_code){
           $this->searchPolyclinics();
       }

       if(strlen($this->form->poly_code) === 0){
           $this->polyclinics = null;
       }
    }

    private function searchPolyclinics() : void
    {
       $this->polyclinics = $this->polyclinicService
            ->searchPolyclinics($this->form->poly_code);
    }

    public function selectPoly($polyCode) : void
    {
        $this->form->poly_code = $polyCode;
        $this->polyclinics = null;
        $this->selectedPolyclinic = $polyCode;
    }

    private function validateCapacities(): void
    {
        if (!$this->form->non_bpjs_capacity || !$this->form->bpjs_capacity) {
            return;
        }

        $totalCapacity = $this->form->non_bpjs_capacity + $this->form->bpjs_capacity;
        $nonBPJSPercentage = ($this->form->non_bpjs_capacity / $totalCapacity) * 100;
        $BPJSPercentage = ($this->form->bpjs_capacity / $totalCapacity) * 100;

        if ((int)$nonBPJSPercentage !== 20) {
            $this->form->addError('non_bpjs_capacity', 'Kapasitas pasien umum harus 20% dari kapasitas total');
        }

        if ((int)$BPJSPercentage !== 80) {
            $this->form->addError('bpjs_capacity', 'Kapasitas pasien BPJS harus 80% dari kapasitas total');
        }

        if ((int)$BPJSPercentage === 80 && (int)$nonBPJSPercentage === 20) {
            $this->form->resetErrorBag();
        }
    }

    private function validatePracticeHours(): void
    {
        if (!$this->form->close_practice_hour || !$this->form->open_practice_hour) {
            return;
        }

        $openPracticeHour = Carbon::parse($this->form->open_practice_hour);
        $closePracticeHour = Carbon::parse($this->form->close_practice_hour);

        if ($openPracticeHour->greaterThan($closePracticeHour)) {
            $this->form->addError('close_practice_hour', 'Jam praktek tutup harus lebih besar dari jam praktek buka');
        }

        if ($openPracticeHour->diffInMinutes($closePracticeHour) < 10) {
            $this->form->addError('close_practice_hour', 'Jam praktek tutup minimal 10 menit setelah jam praktek buka');
        }
    }

    public function getDayName($dayIsoFormat): string
    {
        $dayIsoFormat = (int)$dayIsoFormat;
        return match ($dayIsoFormat) {
            1 => "Senin",
            2 => "Selasa",
            3 => "Rabu",
            4 => "Kamis",
            5 => "Jum'at",
            6 => "Sabtu",
            7 => "Minggu",
            default => "Unknown",
        };
    }
}
