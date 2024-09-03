<?php

namespace App\Repositories\Interfaces;

interface PatientInterface
{
   public function getPatientByRM($RMNumber);

   public function getPatientByNIK($NIKNumber);

    public function getPatientByBPJSNumber($BPJSNumber);


}
