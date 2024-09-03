<?php

namespace App\DTO\BPJS;
use Carbon\Carbon;

class QueueAddDTO
{

    public string $bookingCode;
    public string $patientType;
    public string $cardNumber;
    public string $nik;
    public string $phoneNumber;
    public string $polyCode;
    public string $polyName;
    public int $newPatient;
    public string $RMNumber;
    public string $checkupDate;
    public int $doctorCode;
    public string $doctorName;
    public string $practiceHours;
    public int $typeOfVisit;
    public string $referenceNumber;
    public int $admissionQueueNumber;
    public string $queueNumber;
    public float | int $estimateTimeServed;
    public int $remainingJKNQuota;
    public int $JKNQuota;
    public int $remainingNonJKNQuota;
    public int $nonJKNQuota;
    public string $description;

    public function __construct(object $data){
        $practiceHours = explode('-', $data->practiceHours);
        $this->bookingCode = $data->bookingCode;
        $this->patientType = $data->patientType === 1 ? 'JKN' : 'NON JKN';
        $this->cardNumber = $data->cardNumber;
        $this->nik = $data->nik;
        $this->phoneNumber = $data->phoneNumber;
        $this->polyCode = $data->polyCode;
        $this->polyName = $data->polyName;
        $this->newPatient = $data->newPatient ?? 0;
        $this->RMNumber = $data->RMNumber;
        $this->checkupDate = $data->checkupDate;
        $this->doctorCode = $data->doctorCode;
        $this->doctorName = $data->doctorName;
        $this->practiceHours = Carbon::parse($practiceHours[0])->format('H:i') . '-' .
            Carbon::parse($practiceHours[1])->format('H:i');
        $this->typeOfVisit = $data->typeOfVisit;
        $this->referenceNumber = $data->referenceNumber;
        $this->admissionQueueNumber = $data->admissionQueueNumber;
        $this->queueNumber = $data->queueCode . '-' . $data->admissionQueueNumber;
        $this->estimateTimeServed = $data->estimateTimeServed;
        $this->remainingJKNQuota = $data->remainingJKNQuota;
        $this->JKNQuota = $data->JKNQuota;
        $this->remainingNonJKNQuota = $data->remainingNonJKNQuota;
        $this->nonJKNQuota = $data->nonJKNQuota;
        $this->description = $data->description ?? "Peserta harap 30 menit lebih awal guna pencatatan administrasi.";
    }

    public function toRequestBPJS():array
    {
        return [
            'kodebooking' => $this->bookingCode,
            'jenispasien' => $this->patientType,
            'nomorkartu' => $this->cardNumber,
            'nik' => $this->nik,
            'nohp' => $this->phoneNumber,
            'kodepoli' => $this->polyCode,
            'namapoli' => $this->polyName,
            'pasienbaru' => $this->newPatient,
            'norm' => $this->RMNumber,
            'tanggalperiksa' => $this->checkupDate,
            'kodedokter' => $this->doctorCode,
            'namadokter' => $this->doctorName,
            'jampraktek' => $this->practiceHours,
            'jeniskunjungan' => $this->typeOfVisit,
            'nomorreferensi' => $this->referenceNumber,
            'nomorantrean' => $this->queueNumber,
            'angkaantrean' => $this->admissionQueueNumber ,
            'estimasidilayani' => $this->estimateTimeServed,
            'sisakuotajkn' => $this->remainingJKNQuota,
            'kuotajkn' => $this->JKNQuota,
            'sisakuotanonjkn' => $this->remainingNonJKNQuota,
            'kuotanonjkn' => $this->nonJKNQuota,
            'keterangan' => $this->description
        ];
    }

}
