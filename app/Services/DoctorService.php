<?php

namespace App\Services;

use App\Repositories\Interfaces\DoctorInterface;
use Carbon\Carbon;

class DoctorService
{
    private DoctorScheduleService $doctorScheduleService;
    public function __construct(
        private DoctorInterface $doctorRepository,
        DoctorScheduleService            $doctorScheduleService
    )
    {
        $this->doctorScheduleService = $doctorScheduleService;
    }

    public function getDoctorById(int $id)
    {
        return $this->doctorRepository->getDoctorById($id);
    }

    public function getDoctorByPoly($polyCode)
    {
        return $this->doctorRepository->getDoctorByPoly($polyCode);
    }

    public function update($id, $doctorCode, $data)
    {
        $doctor = $this->doctorRepository->getDoctorById($id);

        if($doctorCode !== $data['code']){
            $schedules = $this->doctorScheduleService->getScheduleByDoctorCode($doctorCode);

            if($schedules){
                $this->doctorScheduleService->massUpdate($schedules, ['kode_dokter' => $data['code']]);
            }
        }

        return $this->doctorRepository->update($doctor, $data);
    }

    public function create($data)
    {
        return $this->doctorRepository->create([
            'nama' => $data['name'],
            'kode' => $data['code'],
        ]);
    }

    public function getStatistics()
    {
        $currentDay = Carbon::now()->dayOfWeekIso;
        return $this->doctorRepository->getStatistics($currentDay);
    }

}

