<?php

namespace App\Livewire\Queue;

use App\Models\Polyclinic;
use App\Services\DoctorScheduleService;
use App\Services\PolyclinicService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class QueueDetail extends Component
{
    public string $polyCode;
    public bool $openModal = false;
    public bool $scheduleDrawer = false;
    public $doctorSchedules;
    public Collection $selectedDoctor;
    public Polyclinic $polyclinic;
    public Collection $schedules;
    protected PolyclinicService $polyclinicService;
    protected DoctorScheduleService $doctorScheduleService;

    public function boot(
        PolyclinicService     $polyclinicService,
        DoctorScheduleService $doctorScheduleService
    ): void {
        $this->polyclinicService = $polyclinicService;
        $this->doctorScheduleService = $doctorScheduleService;
    }


    public function mount(): void
    {
        $this->polyclinic = $this->polyclinicService->getByCode($this->polyCode);

        $this->schedules = $this->doctorScheduleService
            ->getScheduleByPolyAndDay($this->polyCode);
    }

    public function render(): View
    {
        return view('livewire.queue.queue-detail')
            ->layout('layouts.queue', [
                'title' => "POLI " . $this->polyclinic->nama
            ]);
    }

    public function doctorPracticeSchedule($schedules, $doctorName)
    {
        $this->doctorSchedules['schedules'] = json_decode($schedules, true);
        $this->doctorSchedules['doctor_name'] = $doctorName;
        $this->scheduleDrawer = true;
    }

    public function selectDoctor($doctorCode, $doctorName, $schedule)
    {
        if (count($schedule) === 1) {
            return $this->redirect(
                "/antrean/poliklinik/" . $this->polyCode .
                    "/dokter/" . $doctorCode . "?kunjugan=reguler&jam_praktek_buka=" . $schedule[0]['jam_praktek_buka'] . "&jam_praktek_tutup=" . $schedule[0]['jam_praktek_tutup'],
                navigate: true
            );
        }

        $this->selectedDoctor = collect(
            [
                'doctor_code' => $doctorCode,
                'doctor_name' => $doctorName,
                'schedules' => $schedule
            ]
        );
        return $this->openModal = true;
    }

    public function registrationAvailability($schedules): bool
    {
        $schedules = json_decode($schedules, true);
        $currentTime = Carbon::now();

        if (count($schedules) === 1) {
            if ($currentTime->greaterThan($schedules[0]['jam_praktek_tutup'])) return false;
            if (
                $currentTime->lessThan($schedules[0]['jam_praktek_tutup'])
                && $schedules[0]['total_antrian'] === $schedules[0]['kapasitas_pasien']
            ) return false;
        }

        if (count($schedules) > 1) {
            if (
                $currentTime->greaterThan($schedules[0]['jam_praktek_tutup'])
                && $currentTime->greaterThan($schedules[1]['jam_praktek_tutup'])
            ) return false;

            if (
                (int)$schedules[0]['total_antrian'] === (int)$schedules[0]['kapasitas_pasien']
                && (int)$schedules[1]['total_antrian'] === (int)$schedules[1]['kapasitas_pasien']
            ) return false;

            if (
                $currentTime->lessThan($schedules[0]['jam_praktek_tutup'])
                && $currentTime->lessThan($schedules[1]['jam_praktek_tutup'])
                && (int)$schedules[0]['total_antrian'] === (int)$schedules[0]['kapasitas_pasien']
                && (int)$schedules[1]['total_antrian'] === (int)$schedules[1]['kapasitas_pasien']
            ) return false;

            if (
                $currentTime->lessThan($schedules[0]['jam_praktek_tutup'])
                && $currentTime->greaterThan($schedules[1]['jam_praktek_tutup'])
                && (int)$schedules[0]['total_antrian'] === (int)$schedules[0]['kapasitas_pasien']
            ) return false;

            if (
                $currentTime->lessThan($schedules[1]['jam_praktek_tutup'])
                && $currentTime->greaterThan($schedules[0]['jam_praktek_tutup'])
                && (int)$schedules[1]['total_antrian'] === (int)$schedules[1]['kapasitas_pasien']
            ) return false;
        }

        return true;
    }

    public function validationRegistrationModal($closePracticeTime, $queueTotal, $queueCapacity): bool
    {
        $currentTime = Carbon::now();

        if ($currentTime->greaterThan($closePracticeTime)) return false;
        if ($currentTime->lessThan($closePracticeTime) && $queueTotal === $queueCapacity) return false;

        return true;
    }


    public function getDayName($dayIsoFormat): string
    {
        $dayIsoFormat = (int)$dayIsoFormat;
        return match ($dayIsoFormat) {
            1 => "Senin",
            2 => "Selasa",
            3 => "Rabu",
            4 => "Kamis",
            5 => "Jum'at",
            6 => "Sabtu",
            7 => "Minggu",
            default => "Unknown",
        };
    }
}
