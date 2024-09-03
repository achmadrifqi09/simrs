<?php

namespace App\Livewire\Queue\Addition;

use App\Services\PatientService;
use App\Services\QueueService;
use Exception;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class QueueReprint extends Component
{
    public string $identifierNumber = "";
    public bool $isButtonSubmit = false;
    protected QueueService $queueService;
    protected PatientService $patientService;

    public function boot(
        PatientService $patientService,
        QueueService $queueService,
    ): void {
        $this->patientService = $patientService;
        $this->queueService = $queueService;
    }

    public function updated(): void
    {
        $identifierNumberLength = strlen($this->identifierNumber);
        if (in_array($identifierNumberLength, [7, 13, 16])) {
            $this->resetValidation('identifierNumber');
            $this->isButtonSubmit = true;
        } else {
            $this->isButtonSubmit = false;
            $this->addError('identifierNumber', "Angka wajib di isi dan harus 7 digit untuk No RM, 13 digit untuk No BPJS, 16 digit untuk No NIK");
        }
    }

    public function submit()
    {
        try {
            $patient = (object) $this->getPatientByIdentifierNumber($this->identifierNumber);

            $result = $this->queueService->reprintQueue($patient->kode_rm);
            $this->dispatch('reprint-success', $result);
        } catch (Exception $e) {
            $this->dispatch('reprint-notification', [
                'title' => 'Terjadi Kesalahan',
                'message' => $e->getMessage(),
            ]);
        }
    }

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

    #[Title('Cetak Ulang Tiket')]
    public function render(): View
    {
        return view('livewire.queue.addition.queue-reprint')
            ->layout('layouts.queue');
    }
}
