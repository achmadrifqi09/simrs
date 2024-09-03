<?php

namespace App\Repositories\Interfaces;

use App\Models\DoctorSchedule;

interface DoctorScheduleInterface
{
    public function getCurrentScheduleWithQuota(string $doctorCode, int $day, string $checkupDate,string $openPracticeHour,string $closePracticeHour);
    public function getScheduleByPolyAndDay(string $polyCode, int $day);

    public function getCurrentDoctorPractice(string $doctorCode, string $practiceStartHour, string $practiceEndHour, int $day);
    public function getDoctorScheduleWithQuota(string $checkupDate, int $day, string $polyCode);

    public function createSchedule(array $schedule);
    public function getScheduleByDoctorCode(string $doctorCode);
    public function updateSchedule(DoctorSchedule $schedule, array $data);
    public function massDeleteByDoctorCode(string $doctorCode);
}
