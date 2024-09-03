<?php

namespace App\Livewire\Doctor;

use App\Models\Polyclinic;
use App\Services\PolyclinicService;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class DoctorMonthlySchedule extends Component
{
    protected PolyclinicService $polyclinicService;
    public $polyclinics;

    public function boot(PolyclinicService $polyclinicService)
    {
        $this->polyclinicService = $polyclinicService;
    }

    public function mount()
    {
        $this->polyclinics = $this->polyclinicService->getPolyclinicsWithSchedule();
    }

    #[Title('Jadwal Bulanan')]
    public function render() : View
    {

        return view('livewire.doctor.doctor-monthly-schedule')
            ->layout('layouts.basic');
    }
}
