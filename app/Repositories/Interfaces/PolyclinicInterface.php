<?php

namespace App\Repositories\Interfaces;

interface PolyclinicInterface
{
    public function getAllPolyclinics();
    public function getPolyclinicByCode($polyCode);
    public function getPolyclinicByName(string $name);
    public function updateOrCreate($data);

    public function getPolyclinicWithSchedule();
    public function searchPolyclinic(string $keyword);
}
