<?php

namespace App\DTO;

class AdmissionQueueDTO
{

    public string $bookingCode;
    public int $queueNumber;
    public string $queueCode;
    public int $serviceStatus;
    public function __construct(object $data){
        $this->bookingCode = $data->bookingCode;
        $this->queueNumber = $data->admissionQueueNumber;
        $this->queueCode = $data->queueCode;
        $this->serviceStatus = $data->serviceStatus;
    }

    public function toAdmissionQueueModel() : array
    {
        return [
            'nomor_antrian' => $this->queueNumber,
            'kode_booking' => $this->bookingCode,
            'kode_antrian' => $this->queueCode,
            'status_pelayanan' => $this->serviceStatus,
        ];
    }
}


