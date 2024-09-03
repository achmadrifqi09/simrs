<?php

namespace App\Livewire\Forms\Doctor;

use App\Models\Doctor;
use Illuminate\Validation\Rule;
use Livewire\Form;

class DoctorForm extends Form
{
    public Doctor | null $doctor = null;
    public string $name = "";
    public string $code = "";

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:4',
            'code' => [
                'required',
                'min:3',
                Rule::unique('dokters', 'kode')->ignore($this->doctor ?? null),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.min' => 'Nama minimal 4 karakter',
            'code.required' => 'Kode tidak boleh kosong',
            'code.min' => 'Kode minimal 4 karakter',
            'code.unique' => 'Kode sudah terpakai'
        ];
    }

}
