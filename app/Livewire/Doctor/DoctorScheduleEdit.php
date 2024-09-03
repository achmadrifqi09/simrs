<?php

namespace App\Livewire\Doctor;

use App\Livewire\Forms\Doctor\DoctorForm;
use App\Models\Doctor;
use App\Services\DoctorService;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class DoctorScheduleEdit extends Component
{

    public int $id;
    public Doctor $doctor;
    public DoctorForm $form;
    protected DoctorService $doctorService;

    public function boot(DoctorService $doctorService): void
    {
        $this->doctorService = $doctorService;
    }

    public function mount(): void
    {
        $this->doctor = $this->doctorService->getDoctorById($this->id);

        $this->form->fill([
            'name' => $this->doctor->nama,
            'code' => $this->doctor->kode,
            'doctor' => $this->doctor,
        ]);
    }

    public function submit(): void
    {
        $this->form->validate();
        $this->doctorService->update(
            $this->doctor->id,
            ['name' => $this->form->name, 'code' => $this->form->code]
        );

        $this->dispatch('notification', ['message' => 'Berhasil mengubah data dokter']);
    }

    #[Title("Edit Dokter")]
    public function render(): View
    {
        return view('livewire.doctor.doctor-schedule-edit');
    }
}
