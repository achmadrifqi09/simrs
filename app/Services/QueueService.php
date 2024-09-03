<?php

namespace App\Services;

use App\Repositories\Interfaces\QueueInterface;
use App\Utils\QueueHelper;
use Carbon\Carbon;
use Exception;
use App\DTO\AdmissionQueueDTO;
use App\DTO\PolyQueueDTO;
use Illuminate\Support\Collection;
use App\DTO\BPJS\QueueAddDTO;
use App\Utils\ClientRequest;

class QueueService
{
    private PatientService $patientService;
    private DoctorScheduleService $doctorScheduleService;
    private BPJSQueueResponseService $bpjsQueueResponseService;

    public function __construct(
        protected QueueInterface $queueRepository,
        PatientService           $patientService,
        DoctorScheduleService    $doctorScheduleService,
        BPJSQueueResponseService $bpjsQueueResponseService
    )
    {
        $this->patientService = $patientService;
        $this->doctorScheduleService = $doctorScheduleService;
        $this->bpjsQueueResponseService = $bpjsQueueResponseService;
    }

    /**
     * @throws Exception
     * error handling must be provided
     */
    public function oldPatientQueueRegister(object $data) : Collection
    {
        try {
            $checkupDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $checkupDay = Carbon::now()->dayOfWeekIso;
            $now = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

            $practiceHours = explode("-", $data->practiceHours);

            $this->validationPracticeSchedule($data->doctorCode, $practiceHours[0], $practiceHours[1]);

            $patient = isset($data->cardNumber) ? $this->patientService->getPatientByBPJSNumber($data->cardNumber) :
                $this->patientService->getPatientByNIK($data->nik);

            //prepare requirement data
            $data->queueCode = $data->patientType == 0 ? 'C' :  QueueHelper::createQueueCode($practiceHours[0]);
            $data->admissionQueueNumber = $this->getAdmissionQueueNumber($data->queueCode);
            $data->polyQueueNumber = $this->getPolyQueueNumber(
                $data->polyCode,
                $data->doctorCode,
                $practiceHours[0]
            );
            $data->serviceStatus = 3;
            $data->phoneNumber = $patient->telepon;
            $data->RMNumber = $patient->kode_rm;
            $data->nik = $patient->nik;
            $data->day = $checkupDay;
            $data->openPracticeHour = $practiceHours[0];
            $data->closePracticeHour = $practiceHours[1];
            $data->checkupDate = $checkupDate;
            $data->bookingCode = $this->generateBookingCode();

            //get remaining queue admission
            $totalAdmissionQueueNotServed = $this->queueRepository->remainingQueueAdmission(
                $checkupDate,
                $data->queueCode,
                $data->admissionQueueNumber
            );

            $data->estimateTimeServed = QueueHelper::generateServiceTime($totalAdmissionQueueNotServed, $checkupDate, $practiceHours[0]);
            $remainingQueue = $this->getRemainingQueue(
                $data->polyCode,
                $data->doctorCode,
                $checkupDate,
                $data->practiceHours,
            );

            $data = (object) array_merge((array) $data, $remainingQueue);

//            if($data->patientType === 1){
//                $payloadBPJS = new QueueAddDTO($data);
//                $response = ClientRequest::post('antrean/add', $payloadBPJS->toRequestBPJS(), 'queue', ['Content-Type' => 'Application/x-www-form-urlencoded'] );
//
//                $this->bpjsQueueResponseService->create(array_merge([
//                    'nik' =>$data->nik,
//                    'bpjs_number' => $data->cardNumber,
//                    'rm_number' => $data->RMNumber,
//                ], (array)$response));
//
//                if($response['metadata']['code'] !== 200)
//                    throw new Exception($response['metadata']['message']);
//            }

            $admissionData = new AdmissionQueueDTO($data);
            $admissionQueue = $this->queueRepository
                ->createAdmissionQueue($admissionData->toAdmissionQueueModel());
            $data->admissionQueueId = $admissionQueue->id;

            $polyQueueData = new PolyQueueDTO($data);
            $polyQueue = $this->queueRepository
                ->createPolyQueue($polyQueueData->toPolyQueueModel());

            return collect([
                'register_date' => $now,
                'booking_code' => $admissionQueue->kode_booking,
                'queue_number' => $admissionQueue->kode_antrian . "-" . $admissionQueue->nomor_antrian,
                'rm_code' =>  $polyQueue->nomor_rm,
                'patient_name' => $patient->nama,
                'poly_name' => $data->polyName,
                'doctor_name' => $data->doctorName,
                'remaining_queue' => $totalAdmissionQueueNotServed,
            ]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }


    /**
     * @throws Exception
     */
    private function validationPracticeSchedule($doctorCode, $openPracticeHour, $closePracticeHour): void
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $practiceSchedule = $this->doctorScheduleService
            ->getCurrentDoctorPractice($doctorCode, $openPracticeHour, $closePracticeHour);

        if ($currentTime->greaterThan($practiceSchedule->jam_praktek_tutup))
            throw new Exception("Jam praktek " . $practiceSchedule->nama . " telah tutup");
    }

    private function generateBookingCode(): string
    {
        $currenDate = Carbon::now('Asia/Jakarta');
        $admissionTotal = $this->queueRepository
            ->currentAdmissionTotal($currenDate->format('Y-m-d'));

        return $currenDate->format('y') . $currenDate->format('m') .
            $currenDate->format('d') . $admissionTotal + 1;
    }

    private function getAdmissionQueueNumber($queueNumber): int
    {
        $currenDate = Carbon::now('Asia/Jakarta');
        $admissionTotal = $this->queueRepository
            ->currentAdmissionTotalByCode($currenDate->format('Y-m-d'), $queueNumber);

        return $admissionTotal + 1;
    }

    private function getPolyQueueNumber($polyCode, $doctorCode, $openPracticeHour) : int
    {
        $currenDate = Carbon::now('Asia/Jakarta');
        $polyTotal = $this->queueRepository
            ->currentPolyTotal($currenDate
                ->format('Y-m-d'), $polyCode, $doctorCode, $openPracticeHour);

        return $polyTotal + 1;
    }

    public function getRemainingQueue($polyCode, $doctorCode, $checkupDate, $practiceHour) : array
    {
        $practiceHours = explode("-", $practiceHour);

        $doctorSchedule = $this->doctorScheduleService
            ->getCurrentDoctorPractice($doctorCode, $practiceHours[0], $practiceHours[1]);

        $remainingQueueJKN = $this->queueRepository
            ->JKNQueueTotal($polyCode, $doctorCode, $checkupDate, $practiceHours[0]);

        $remainingQueueNonJKN = $this->queueRepository
            ->nonJKNQueueTotal($polyCode, $doctorCode, $checkupDate, $practiceHour);

        return [
            'JKNQuota' => $doctorSchedule->kapasitas_pasien_bpjs,
            'remainingJKNQuota' => (int) $doctorSchedule->kapasitas_pasien_bpjs - $remainingQueueJKN,
            'nonJKNQuota' => $doctorSchedule->kapasitas_pasien_non_bpjs,
            'remainingNonJKNQuota' => (int) $doctorSchedule->kapasitas_pasien_non_bpjs - $remainingQueueNonJKN,
        ];
    }

    public function reprintQueue($RMNumber): Collection
    {
        try{
            $currentDate = Carbon::now('Asia/Jakarta')
                ->format('Y-m-d');
            $now = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');

            $result = $this->queueRepository
                ->getQueueByRMNumber($currentDate, $RMNumber);

            if(!$result) throw new Exception("Antrean tidak ditemukan, pastikan anda telah mengamil nomor antrean");

            $remainingQueue = $this->queueRepository
                ->remainingQueueAdmission($currentDate, $result->kode_antrian, $result->nomor_antrian_admisi);
            return collect([
                'register_date' => $now,
                'booking_code' => $result->kode_booking,
                'queue_number' => $result->kode_antrian . "-" . $result->nomor_antrian_admisi,
                'rm_code' =>  $result->nomor_rm,
                'patient_name' => $result->nama,
                'poly_name' => $result->nama_poli,
                'doctor_name' => $result->nama_dokter,
                'remaining_queue' => $remainingQueue,
            ]);
        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

}
