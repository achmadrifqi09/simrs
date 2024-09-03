<?php

namespace App\Livewire\SEP;

use Livewire\Attributes\Title;
use Livewire\Component;

class Sep extends Component
{


    #[Title('Pengeloaan SEP')]
    public function render()
    {
        return view('livewire.s-e-p.sep');
    }
}
