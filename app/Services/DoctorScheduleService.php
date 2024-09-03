<?php

namespace App\Services;

use App\DTO\DoctorScheduleDTO;
use App\Models\DoctorSchedule;
use App\Repositories\Interfaces\DoctorScheduleInterface;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DoctorScheduleService
{
    public function __construct(
        protected DoctorScheduleInterface $doctorScheduleRepository)
    {
    }

    public function getScheduleByPolyAndDay($polyCode): Collection
    {
        $day = Carbon::now('Asia/Jakarta')
            ->dayOfWeekIso;
        $currentDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');

        $doctors = $this->doctorScheduleRepository
            ->getScheduleByPolyAndDay($polyCode, $day);
        $doctors = collect($doctors);

        $doctorScheduleWithQuota = $this->doctorScheduleRepository
            ->getDoctorScheduleWithQuota($currentDate, $day, $polyCode);


        return $doctors->map(function ($doctor) use ($doctorScheduleWithQuota) {
            $doctor->jadwal_praktek = collect(json_decode($doctor->jadwal_praktek, true))
                ->groupBy('hari_praktek');

            $result = $doctor->jadwal_praktek->map(function ($schedules) use ($doctorScheduleWithQuota, $doctor) {
                return $schedules->map(function ($schedule) use ($doctorScheduleWithQuota, $doctor, $schedules) {
                    foreach ($doctorScheduleWithQuota as $quota) {

                        if (
                            $schedule['jam_praktek_buka'] === $quota->jam_praktek_buka
                            && $schedule['jam_praktek_tutup'] === $quota->jam_praktek_tutup
                            && $schedule['hari_praktek'] === $quota->hari
                            && $doctor->kode === $quota->kode) {

                            $schedule['total_antrian'] = $quota->total_antrian;
                            $schedule['kapasitas_pasien'] = $quota->kapasitas_pasien;
                            $schedule['libur'] = (int) $quota->libur;
                            $doctor->jadwal_sekarang[] = $schedule;

                            if (count($doctor->jadwal_sekarang) > 1) {
                                usort($doctor->jadwal_sekarang, [$this, 'compareTimes']);
                            }
                        }
                    }
                    return $schedule;
                });
            });
            $doctor->jadwal_praktek = $result;
            return $doctor;
        });

    }

    public function getCurrentDoctorPractice($doctorCode, $practiceStartHour, $practiceEndHour)
    {
        $day = Carbon::now('Asia/Jakarta')
            ->dayOfWeekIso;
        return $this->doctorScheduleRepository
            ->getCurrentDoctorPractice($doctorCode, $practiceStartHour, $practiceEndHour, $day);
    }

    /**
     * @throws Exception
     */
    public function createSchedule(array $schedule)
    {
        $scheduleData = $this->getDTO($schedule);

        return $this->doctorScheduleRepository->createSchedule($scheduleData->toDoctorScheduleModel());
    }

    /**
     * @param array $schedule
     * @param int $id
     * @return DoctorScheduleDTO
     * @throws Exception
     */
    public function getDTO(array $schedule, int $id = 0): DoctorScheduleDTO
    {
        $schedules = $this->getScheduleByDoctorCode($schedule['doctor_code'])->get();
        if ($schedules && isset($schedules[0]->kode_poli) && $schedules[0]->kode_poli !== $schedule['poly_code']) throw new Exception('Kode poliklinik tidak sesuai dengan jadwal sebelumnya');

        $this->validatePracticeHour($schedules, $schedule['open_practice_hour'], $schedule['close_practice_hour'], $schedule['day'], $id);

        return new DoctorScheduleDTO((object)$schedule);
    }

    public function getScheduleByDoctorCode($doctorCode)
    {
        return $this->doctorScheduleRepository
            ->getScheduleByDoctorCode($doctorCode);
    }

    /**
     * @throws Exception
     */
    private function validatePracticeHour($schedules, $openHour, $closeHour, $day, $id = 0): void
    {

        $openPracticeHour = Carbon::createFromFormat('H:i:s', $openHour);
        $closePracticeHour = Carbon::createFromFormat('H:i:s', $closeHour);
        foreach ($schedules as $practiceSchedule) {
            $existingOpenPracticeHour = Carbon::createFromFormat('H:i:s', $practiceSchedule->jam_praktek_buka)->format('H:i:s');
            $existingClosePracticeHour = Carbon::createFromFormat('H:i:s', $practiceSchedule->jam_praktek_tutup)->format('H:i:s');

            if (($openPracticeHour->between($existingOpenPracticeHour, $existingClosePracticeHour)
                    || $closePracticeHour->between($existingOpenPracticeHour, $existingClosePracticeHour))
                    && $day == $practiceSchedule->hari && $id !== $practiceSchedule->id) {
                throw new Exception('Jam praktek bertentangan dengan jadwal yang telah ada');
            }

            if($openPracticeHour->lessThan($existingOpenPracticeHour)
                    && $closePracticeHour->greaterThan($existingClosePracticeHour)
                    && $day == $practiceSchedule->hari && $id !== $practiceSchedule->id){
                throw new Exception('Jam praktek bertentangan dengan jadwal yang telah ada');
            }
        }
    }

    /**
     * @throws Exception
     */
    public function updateSchedule(array $schedule, int $id)
    {
        $scheduleData = $this->getDTO($schedule, $id);
        $scheduleModel = $this->doctorScheduleRepository->getScheduleById($id);

        if (!$scheduleModel) throw new Exception('Jadwal tidak ditemukan');

        return $this->doctorScheduleRepository->updateSchedule($scheduleModel, $scheduleData->toDoctorScheduleModel());
    }


   public function massUpdate(DoctorSchedule | Builder $schedule, array $data)
   {
       return $this->doctorScheduleRepository->updateSchedule($schedule, $data);
   }

    /**
     * @throws Exception
     */
    private function compareTimes($firstTime, $secondTime): int
    {
        $timeA = $this->toStandardTime($firstTime['jam_praktek_buka']);
        $timeB = $this->toStandardTime($secondTime['jam_praktek_buka']);
        return strcmp($timeA, $timeB);
    }

    public function massDeleteByDoctorCode(string $doctorCode)
    {
        return $this->doctorScheduleRepository->massDeleteByDoctorCode($doctorCode);
    }

    /**
     * @throws Exception
     */
    private function toStandardTime($time): string
    {
        if (strlen($time) == 5) {
            $time .= ':00';
        }

        $dateTime = new DateTime($time);
        return $dateTime->format('H:i:s');
    }
}
