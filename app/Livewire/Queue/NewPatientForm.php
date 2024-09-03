<?php

namespace App\Livewire\Queue;

use Livewire\Component;

class NewPatientForm extends Component
{
    public function render()
    {
        return view('livewire.queue.new-patient-form')
            ->layout('layouts.queue');
    }
}
