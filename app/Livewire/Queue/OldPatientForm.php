<?php

namespace App\Livewire\Queue;

use App\Livewire\Forms\Queue\QueueOldPatientForm;
use App\Services\PatientService;
use App\Services\QueueService;
use App\Utils\ClientRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;


class OldPatientForm extends Component
{
    public QueueOldPatientForm $oldPatientForm;
    public Collection|null $selectedPatientData = null;
    protected PatientService $patientService;
    protected QueueService $queueService;
    public Collection $passData;
    public array $response;
    public bool $isButtonSearch = true;
    public bool $BPJSModal = false;

    public function boot(
        PatientService $patientService,
        QueueService $queueService,
    ): void {
        $this->patientService = $patientService;
        $this->queueService = $queueService;
    }

    public function updated($propertyName): void
    {
        $identifierNumber = $this->oldPatientForm->identifier_number;
        $identifierNumberLength = strlen($identifierNumber);
        $patientType = $this->oldPatientForm->patient_type;

        $validLengths = ($patientType === 1) ? [7, 13, 16] : [7, 16];
        $errorMessages = [
            1 => 'Angka harus 7 digit untuk No RM, 13 digit untuk No BPJS dan 16 digit untuk No NIK',
            0 => 'Angka harus 7 digit untuk No RM dan 16 digit untuk No NIK'
        ];

        if (!in_array($identifierNumberLength, $validLengths)) {
            $this->isButtonSearch = true;
            $this->oldPatientForm->addError('identifier_number', $errorMessages[$patientType]);
        } else {
            $this->isButtonSearch = false;
        }

        if (in_array($propertyName, [
            'oldPatientForm.patient_type',
            'oldPatientForm.identifier_number',
            'oldPatientForm.type_of_visit'
        ])) {
            $this->selectedPatientData = null;
        }
    }

    public function selectedRecord($data): void
    {
        try {
            $data = collect($data);
            $currentDate = Carbon::now();

            if (
                $this->oldPatientForm->type_of_visit === 3
                && $currentDate->greaterThan($data['tglRencanaKontrol'] . ' 23:59:59')
            ) {
                throw new Exception("Tanggal rencana kontrol sudah melebihi batas, lebih lanjut silakan menuju ke petugas/loket");
            }

            $this->selectedPatientData = $data;
            $this->BPJSModal = false;
        } catch (Exception $e) {
            $this->BPJSModal = false;
            $this->dispatch('old-patient-notification', [
                'title' => 'Terjadi Kesalahan',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function submit()
    {
        try {
            $payload = (object) $this->generateSubmitPayload();
            $result = $this->queueService->oldPatientQueueRegister($payload);
            $this->dispatch('queue-success-register', $result);
        } catch (Exception $e) {
            $this->selectedPatientData = null;
            $this->dispatch('old-patient-notification', [
                'title' => 'Terjadi Kesalahan',
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function generateSubmitPayload(): array
    {
        $patientForm = $this->oldPatientForm;
        $payload = [
            'patientType' => $this->oldPatientForm->patient_type,
            'polyCode' => $this->passData['polyCode'],
            'polyName' => $this->passData['polyName'],
            'doctorCode' => $this->passData['doctorCode'],
            'doctorName' => $this->passData['doctorName'],
            'practiceHours' => $this->passData['practiceHours'],
            'typeOfVisit' => $patientForm->patient_type === 1 ? $patientForm->type_of_visit : 0,
        ];

        if (isset($this->selectedPatientData['nik'])) $payload['nik'] =  $this->selectedPatientData['nik'];
        if ($this->oldPatientForm->patient_type === 1) {
            $payload['cardNumber'] = $this->getCardNumber();
            $payload['referenceNumber'] = $this->getReferenceNumber();
        }

        return $payload;
    }

    private function getCardNumber()
    {
        if ($this->oldPatientForm->patient_type === 1) {
            switch ($this->oldPatientForm->type_of_visit) {
                case 1:
                    return  $this->selectedPatientData['peserta']['noKartu'];
                case 2:
                    return  $this->selectedPatientData['nomor_kartu'];
                case 3:
                    return $this->selectedPatientData['noKartu'];
            }
        }
    }

    private function getReferenceNumber(): string
    {
        if ($this->oldPatientForm->patient_type === 1) {
            switch ($this->oldPatientForm->type_of_visit) {
                case 1:
                    return $this->selectedPatientData['noKunjungan'];
                case 2:
                    return $this->createReferenceNumberInternal(
                        $this->passData['kodepoli'],
                        $this->selectedPatientData['kode_rm']
                    );
                case 3:
                    return $this->selectedPatientData['noSuratKontrol'];
            }
        }

        return $this->createReferenceNumberInternal(
            $this->passData['kodepoli'],
            $this->selectedPatientData['kode_rm']
        );
    }

    private function createReferenceNumberInternal($polyCode, $RMNumber): string
    {
        $currentDate = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        return str_replace("-", "", $currentDate) . $polyCode . $RMNumber;
    }

    public function render(): View
    {
        return view('livewire.queue.old-patient-form')
            ->layout('layouts.queue');
    }


    public function searchIdentifierNumber(): void
    {
        try {
            $this->selectedPatientData = null;

            if ($this->oldPatientForm->patient_type === 0) {
                $patient = self::getPatientByIdentifierNumber($this->oldPatientForm->identifier_number);

                if (!$patient) throw new Exception("Pasien tidak ditemukan");
                $this->selectedPatientData = collect($patient);
                return;
            }

            if ($this->oldPatientForm->type_of_visit === 2) {
                $patient = self::getPatientByIdentifierNumber($this->oldPatientForm->identifier_number);

                if (!$patient) throw new Exception("Pasien tidak ditemukan");
                $this->selectedPatientData = collect($patient);
                return;
            }

            $endpoint = $this->generateEndpoint();
            $this->response = ClientRequest::get($endpoint, 'vclaim');

            if (
                (int)$this->response["metadata"]["code"] == 201
                && $this->oldPatientForm->type_of_visit === 3
            ) {
                $this->dispatch('old-patient-notification', [
                    'title' => 'Terjadi Kesalahan Data Tidak Ditemukan',
                    'message' => "1. Pastikan nomor yang anda masukkan sudah benar
                    2. Pastikan surat kontrol sudah diterbitkan
                    3. Pastikan tanggal kontrol tidak terlewat
                    * Lebih lanjut silakan ke loket/petugas",
                ]);
                return;
            }

            if ((int)$this->response['metadata']['code'] !== 200) {
                $this->dispatch('old-patient-notification', [
                    'title' => 'Terjadi Kesalahan',
                    'message' => $this->response['metadata']['message'],
                ]);
                return;
            }
            $this->BPJSModal = true;
        } catch (Exception $e) {
            $this->BPJSModal = false;
            $this->selectedPatientData = null;
            $this->dispatch('old-patient-notification', [
                'title' => 'Terjadi Kesalahan',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @throws Exception
     */
    private function getPatientByIdentifierNumber($identifierNumber)
    {
        $identifierNumberLength = strlen($identifierNumber);

        try {
            return match ($identifierNumberLength) {
                7 => $this->patientService
                    ->getPatientByRM($identifierNumber),
                13 => $this->patientService
                    ->getPatientByBPJSNumber($identifierNumber),
                default => $this->patientService
                    ->getPatientByNIK($identifierNumber),
            };
        } catch (Exception $e) {
            return throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function generateEndpoint(): array|string
    {
        $identifierNumber = $this->oldPatientForm
            ->identifier_number;

        $endpoint = self::getUrlPath();

        try {
            $patient = self::getPatientByIdentifierNumber($identifierNumber);

            $BPJSNumber = $patient->nomor_kartu;
            $endpoint = str_replace('{identifier_number}', $BPJSNumber, $endpoint);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        return $endpoint;
    }

    private function getUrlPath(): string
    {
        $date_now = Carbon::now();
        $month = $date_now->format('m');
        $year = $date_now->format('Y');

        $paths = [
            1 => "Rujukan/List/Peserta/{identifier_number}",
            3 => "RencanaKontrol/ListRencanaKontrol/Bulan/$month/Tahun/$year/Nokartu/{identifier_number}/filter/2"
        ];

        return $paths[$this->oldPatientForm->type_of_visit] ?? "Rujukan/RS/List/Peserta/{identifier_number}";
    }

    public function getTypeOfVisit($code): string
    {
        return match ($code) {
            2 => "Rujukan Internal",
            3 => "Kontrol",
            4 => "Rujukan Antar RS",
            default => "Rujukan FKTP",
        };
    }
}
