<?php

namespace App\Livewire\Doctor;

use App\Services\DoctorService;
use Livewire\Attributes\Title;
use Livewire\Component;


class Doctor extends Component
{
    public $statistics;
    protected DoctorService $doctorService;

    public function boot(DoctorService $doctorService) : void
    {
        $this->doctorService = $doctorService;
    }

    public function mount() : void
    {
        $this->statistics = $this->doctorService->getStatistics();
    }

    #[Title('Dokter')]
    public function render()
    {
        return view('livewire.doctor.doctor');
    }
}
