<?php

namespace App\Services;

use App\Repositories\Interfaces\PatientInterface;
use Exception;

class PatientService
{
    public function __construct(protected PatientInterface $patientRepository){

    }

    /**
     * @throws Exception
     */
    public function getPatientByRM($RMNumber)
    {
        if(strlen($RMNumber) !== 7) throw new Exception("Nomor RM pasien tidak valid");
        $patient = $this->patientRepository->getPatientByRM($RMNumber);
        return $patient ?? throw new Exception("Pasien tidak ditemukan");
    }


    /**
     * @throws Exception
     */
    public function getPatientByNIK($NIKNumber){
        if(strlen($NIKNumber) !== 16) throw new Exception("NIK tidak valid");

        $patient = $this->patientRepository->getPatientByNIK($NIKNumber);
        return $patient ?? throw new Exception("Pasien tidak ditemukan");
    }

    /**
     * @throws Exception
     */
    public function getPatientByBPJSNumber($BPJSNumber){
        if(strlen($BPJSNumber) !== 13) throw new Exception("Nomor BPJS tidak valid");

        $patient = $this->patientRepository->getPatientByBPJSNumber($BPJSNumber);
        return $patient ?? throw new Exception("Pasien tidak ditemukan atau belum terdaftar di RSU UMM");
    }

}
