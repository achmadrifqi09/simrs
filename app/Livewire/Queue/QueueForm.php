<?php

namespace App\Livewire\Queue;

use App\Services\DoctorScheduleService;
use App\Services\DoctorService;
use App\Services\PolyclinicService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class QueueForm extends Component
{
    public string $polyCode;
    public string $doctorCode;
    public array $practiceHours;
    protected DoctorScheduleService $doctorScheduleService;
    protected PolyclinicService $polyClinicService;
    public string $subTitle = "";
    protected DoctorService $doctorService;
    public $doctorSchedule;
    public array $tabBarItems = [
        ['label' => 'Pasien Lama', 'key' => 'queue.old-patient-form'],
        ['label' => 'Pasien Baru', 'key' => 'queue.new-patient-form']
    ];
    public string $activeTabBar = "queue.old-patient-form";
    public Collection $passData;


    public function boot(
        DoctorScheduleService $doctorScheduleService,
        PolyclinicService $polyClinicService,
        DoctorService $doctorService
    ): void {
        $this->doctorScheduleService = $doctorScheduleService;
        $this->polyClinicService = $polyClinicService;
        $this->doctorService = $doctorService;
    }

    public function mount(): void
    {
        $this->practiceHours = [
            Request::query('jam_praktek_buka'),
            Request::query('jam_praktek_tutup')
        ];
        $visitedType = Request::query('kunjungan');

        if ($visitedType === 'khusus') {
            $this->doctorSchedule = (object) $this->doctorService->getDoctorByPoly($this->polyCode)[0];
            $this->subTitle = "(Khusus Fisioterapi)";
        } else {
            $this->doctorSchedule = $this->doctorScheduleService
                ->getCurrentDoctorPractice(
                    $this->doctorCode,
                    $this->practiceHours[0],
                    $this->practiceHours[1]
                );
        }
        $polyclinic = $this->polyClinicService
            ->getByCode($this->polyCode);

        $this->passData = collect([
            'polyCode' => $this->polyCode,
            'polyName' => $polyclinic->nama,
            'doctorCode' => $this->doctorCode,
            'doctorName' => $this->doctorSchedule->nama,
            'practiceHours' => join("-", $this->practiceHours)
        ]);
    }

    #[Title('Formulir Antrian')]
    public function render(): View
    {
        if (!Request::query('jam_praktek_buka')) return $this->redirect('/poliklinik/' . $this->polyCode);
        return view('livewire.queue.queue-form')
            ->layout('layouts.queue');
    }
}
