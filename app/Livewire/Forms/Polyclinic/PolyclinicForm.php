<?php

namespace App\Livewire\Forms\Polyclinic;


use App\Models\Polyclinic;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PolyclinicForm extends Form
{
    public ?Polyclinic $polyclinic;

    public string $name = "";
    public string $code = "";

    public int $status = 0;

     public function rules(): array
     {
         return [
             'name' => 'required|string|min:4',
             'code' => [
                 'required',
                 'min:3',
                 'max:3',
                 Rule::unique('polis', 'kode')->ignore($this->polyclinic ?? null),
             ],
             'status' => 'required|integer|between:0,1',
         ];
     }

     public function messages(): array
     {
         return [
             'name.required' => 'Nama tidak boleh kosong',
             'name.min' => 'Nama minimal 4 karakter',
             'code.required' => 'Kode tidak boleh kosong',
             'code.min' => 'Kode harus 3 karakter',
             'code.max' => 'Kode harus 3 karakter',
             'status.required' => 'Status tidak boleh kosong',
             'status.integer' => 'Status harus berupa angka',
             'status.between' => 'Status harus berupa 0/1',
             'code.unique' => 'Kode sudah terpakai',
         ];
     }
}
