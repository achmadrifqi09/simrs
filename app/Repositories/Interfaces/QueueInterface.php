<?php

namespace App\Repositories\Interfaces;

interface QueueInterface
{
    public function getQueuePolyclinicSingle($RMCode, $checkupDate, $polyCode, $doctorCode);
    public function currentPolyTotal($checkupDate, $polyCode, $doctorCode, $openPracticeHour);

    public function currentAdmissionTotalByCode($currentDate, $queueCode);
    public function currentAdmissionTotal($currentDate);
    public function createPolyQueue(array $data);
    public function createAdmissionQueue(array $data);
    public function remainingQueueAdmission($checkupDate, $queueCode,  $queueNumber);
    public function getQueueByRMNumber($checkupDate, $RMNumber);

    public function JKNQueueTotal($polyCode, $doctorCode, $checkupDate, $openHourPractice);
    public function nonJKNQueueTotal($polyCode, $doctorCode, $checkupDate, $openHourPractice);
}
