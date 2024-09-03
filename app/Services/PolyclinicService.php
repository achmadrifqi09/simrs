<?php

namespace App\Services;

use App\Models\Polyclinic;
use App\Repositories\Interfaces\PolyclinicInterface;
use Illuminate\Database\Eloquent\Collection;
use function Symfony\Component\Translation\t;

class PolyclinicService
{
    public function __construct(
        protected PolyclinicInterface $polyclinicRepository
    )
    {
    }

    public function all(): Collection
    {
        return $this->polyclinicRepository
            ->getAllPolyclinics();
    }

    public function updateOrCreatePoly($data)
    {
        return $this->polyclinicRepository
            ->updateOrCreate($data);
    }

    public function getByName(string $name)
    {
        return $this->polyclinicRepository
            ->getPolyclinicByName($name);
    }

    public function getByCode(string $code): Polyclinic
    {
        return $this->polyclinicRepository
            ->getPolyclinicByCode($code);
    }

    public function searchPolyclinics(string $keyword)
    {
        return $this->polyclinicRepository->searchPolyclinic($keyword);
    }

    public function getPolyclinicsWithSchedule()
    {
        $queryResult = $this->polyclinicRepository->getPolyclinicWithSchedule();
        $polyclinics = collect($queryResult);
        return $polyclinics->map(function ($polyclinic) {
            $doctorCollection = collect(json_decode($polyclinic->dokter, true));
           $result = $doctorCollection->map(function ($doctor) {
                $doctor['jadwal'] = collect(json_decode($doctor['jadwal']));
                $doctor['jadwal']->sortBy('hari');
                return $doctor;
            });

            $polyclinic->dokter = $result;
            return $polyclinic;
        });
    }
}
