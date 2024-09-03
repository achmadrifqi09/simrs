<?php

namespace App\DTO;

class PolyQueueDTO
{
    public ?string $cardNumber;
    public string $phoneNumber;
    public string $polyCode;
    public string $RMNumber;
    public int $day;
    public string $checkupDate;
    public string $doctorCode;
    public string $openPracticeHour;
    public string $closePracticeHour;
    public ?int $typeOfVisit;
    public ?string $referenceNumber;
    public string $polyQueueNumber;
    public string $queueCode;
    public string $bookingCode;
    public int $serviceStatus;
    public int $admissionQueueId;


    public function __construct(object $data)
    {
        $this->cardNumber = $data->cardNumber ?? null;
        $this->phoneNumber = $data->phoneNumber;
        $this->polyCode = $data->polyCode;
        $this->RMNumber = $data->RMNumber;
        $this->day = $data->day;
        $this->checkupDate = $data->checkupDate;
        $this->doctorCode = $data->doctorCode;
        $this->openPracticeHour = $data->openPracticeHour;
        $this->closePracticeHour = $data->closePracticeHour;
        $this->typeOfVisit = $data->typeOfVisit ?? 0;
        $this->referenceNumber = $data->referenceNumber ?? null;
        $this->polyQueueNumber = $data->polyQueueNumber;
        $this->queueCode = $data->queueCode;
        $this->bookingCode = $data->bookingCode;
        $this->serviceStatus = $data->serviceStatus;
        $this->admissionQueueId = $data->admissionQueueId;
    }

    public function toPolyQueueModel():array {
        return [
            'nomor_kartu' => $this->cardNumber ?? null,
            'nomor_hp' => $this->phoneNumber,
            'kode_poli' => $this->polyCode,
            'nomor_rm' => $this->RMNumber,
            'hari' => $this->day,
            'tanggal_periksa' => $this->checkupDate,
            'kode_dokter' => $this->doctorCode,
            'jam_praktek_buka' => $this->openPracticeHour,
            'jam_praktek_tutup' => $this->closePracticeHour,
            'jenis_kunjungan' => $this->typeOfVisit ?? 0,
            'nomor_referensi' => $this->referenceNumber ?? null,
            'nomor_antrian' => $this->polyQueueNumber,
            'kode_antrian' => $this->queueCode,
            'kode_booking' => $this->bookingCode,
            'status_pelayanan' => $this->serviceStatus,
            'antrian_admisi_id' => $this->admissionQueueId,
        ];
    }

}
