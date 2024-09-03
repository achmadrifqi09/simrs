<?php

namespace App\DTO;

class DoctorScheduleDTO
{
    public int $day;
    public int $BPJSCapacity;
    public int $nonBPJSCapacity;
    public int $capacityTotal;
    public string $openPracticeHour;
    public string $closePracticeHour;
    public string $doctorCode;
    public string $polyCode;
    public int $holiday;


    public function __construct(object $schedule)
    {
        $this->day = $schedule->day;
        $this->BPJSCapacity = $schedule->bpjs_capacity;
        $this->nonBPJSCapacity = $schedule->non_bpjs_capacity;
        $this->capacityTotal = $schedule->bpjs_capacity + $schedule->non_bpjs_capacity;
        $this->openPracticeHour = $schedule->open_practice_hour;
        $this->closePracticeHour = $schedule->close_practice_hour;
        $this->doctorCode = $schedule->doctor_code;
        $this->polyCode = $schedule->poly_code;
        $this->holiday = $schedule->holiday;
    }

    public function toDoctorScheduleModel() : array
    {
        return [
            'hari' => $this->day,
            'kapasitas_pasien_bpjs' => $this->BPJSCapacity,
            'kapasitas_pasien_non_bpjs' => $this->nonBPJSCapacity,
            'kapasitas_pasien' => $this->capacityTotal,
            'jam_praktek_buka' => $this->openPracticeHour,
            'jam_praktek_tutup' => $this->closePracticeHour,
            'kode_dokter' => $this->doctorCode,
            'kode_poli' => $this->polyCode,
            'libur' => $this->holiday,
        ];
    }
}
