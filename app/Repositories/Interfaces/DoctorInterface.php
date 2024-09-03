<?php

namespace App\Repositories\Interfaces;

use App\Models\Doctor;

interface DoctorInterface
{
    public function getDoctorByPoly($polyCode);
    public function getDoctorById($id);
    public function update(Doctor $doctor, $data);
    public function create($data);
    public function getStatistics($day);
}
