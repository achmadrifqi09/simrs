<?php

namespace App\Livewire\Forms\Queue;

use Livewire\Attributes\Validate;
use Livewire\Form;

class QueueOldPatientForm extends Form
{
    #[Validate(['patient_type' => 'required'], message: [
        'patient_type.required' => 'Jenis pasien harus di isi',
    ])]
    public int $patient_type = 1;

    #[Validate(['identifier_number' => 'required'], message: [
        'identifier_number.required' => 'Nomor harus diisi',
    ])]
    public string $identifier_number = "";

    #[Validate(['type_of_visit' => 'required_if:patient_type,1'], message: [
        'type_of_visit.required_if' => 'Jenis pasien harus di isi',
    ])]
    public int | null $type_of_visit = 1;
}
