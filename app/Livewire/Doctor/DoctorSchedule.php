<?php

namespace App\Livewire\Doctor;

use App\Services\DoctorService;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Doctor;

class DoctorSchedule extends Component
{
    protected DoctorService $doctorService;
    public int $id;
    public Doctor $doctor;

    public function boot(DoctorService $doctorService) : void
    {
        $this->doctorService = $doctorService;
    }

    public function mount(): void
    {
        $this->doctor = $this->doctorService->getDoctorById($this->id);
    }

    #[Title("Detail Dokter")]
    public function render() : View
    {
        return view('livewire.doctor.doctor-schedule');
    }
}
