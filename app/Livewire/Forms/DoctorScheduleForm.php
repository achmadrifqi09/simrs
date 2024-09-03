<?php

namespace App\Livewire\Forms;

use App\Models\DoctorSchedule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DoctorScheduleForm extends Form
{
    public ?DoctorSchedule $doctorSchedule = null;

    #[Validate(['day' => 'required|between:1,7'], message: [
        'day.required' => 'Hari harus diisi',
        'day.between' => 'Hari tidak valid',
    ])]
    public int $day = 1;
    #[Validate(['bpjs_capacity' => 'required|not_in:0'], message: [
        'bpjs_capacity.required' => 'Kapasitas BPJS harus diisi',
        'bpjs_capacity.not_in' => 'Kapasitas BPJS tidak boleh 0',
    ])]
    public int | null $bpjs_capacity = 0;

    #[Validate(['non_bpjs_capacity' => 'required|not_in:0'], message: [
        'non_bpjs_capacity.required' => 'Kapasitas Non BPJS harus diisi',
        'non_bpjs_capacity.not_in' => 'Kapasitas Non BPJS tidak boleh 0',
    ])]
    public int | null $non_bpjs_capacity = 0;

    #[Validate(['open_practice_hour' => 'required:date_format:H:i:s'], message: [
        'open_practice_hour.required' => 'Jam praktek buka harus diisi',
        'open_practice_hour.date_format' => 'Jam praktek buka tidak valid',
    ])]
    public string $open_practice_hour = "00:00:00";

    #[Validate(['close_practice_hour' => 'required:date_format:H:i:s'], message: [
        'close_practice_hour.required' => 'Jam praktek buka harus diisi',
        'close_practice_hour.date_format' => 'Jam praktek buka tidak valid',
    ])]
    public string $close_practice_hour = "00:00:00";

    #[Validate(['holiday' => 'required|in:0,1'], message: [
        'holiday.required' => 'Status libur harus diisi',
        'holiday.in' => 'Status libur tidak tidak valid',
    ])]
    public int $holiday = 0;

    #[Validate(['poly_code' => 'required|min:2'], message: [
        'poly_code.required' => 'Kode poliklinik harus diisi',
        'poly_code.min' => 'Kode poliklinik memiliki format minimal 3 karakter',
    ])]
    public string $poly_code = "";
}
