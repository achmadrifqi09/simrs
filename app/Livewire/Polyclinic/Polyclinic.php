<?php

namespace App\Livewire\Polyclinic;

use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Polyclinic extends Component
{
    #[Title('Poliklinik')]
    public function render()
    {
        return view('livewire.polyclinic.polyclinic');
    }
}
