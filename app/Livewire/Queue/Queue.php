<?php

namespace App\Livewire\Queue;

use App\Services\PolyclinicService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;


class Queue extends Component
{
    protected PolyclinicService $polyclinicService;
    public Collection $polyclinics;
    public string $keyword = "";

    public function boot(PolyclinicService $polyclinicService): void
    {
        $this->polyclinicService = $polyclinicService;
    }


    #[Title('Antrean')]
    public function render(): View
    {
        $this->polyclinics = $this->polyclinicService
            ->getByName($this->keyword);
        return view('livewire.queue.queue')
            ->layout('layouts.queue');
    }
}
