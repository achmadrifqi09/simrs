<?php

namespace App\Livewire\Queue\Addition;

use App\Services\DoctorService;
use Illuminate\View\View;
use Livewire\Component;

class PhysiotherapyCard extends Component
{
    public array $physiotherapyDoctors;
    public bool $physiotherapyModal = false;
    private DoctorService $doctorService;

    public function boot(DoctorService $doctorService) : void
    {
        $this->doctorService = $doctorService;
    }

    public function mount():void
    {
        $this->physiotherapyDoctors = $this->doctorService
            ->getDoctorByPoly('IRM');
    }

    public function render() : View
    {
        return view('livewire.queue.addition.physiotherapy-card');
    }

    public function handleOpenModal()
    {
        $this->physiotherapyModal = true;
    }
}
