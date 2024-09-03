<div
    x-on:old-patient-notification="swal({title: $event.detail[0].title, text: $event.detail[0].message})"
    x-on:queue-success-register="swal(
    {
        title : 'Berhasil',
        text: 'Selamat pendaftaran antrean anda berhasil',
        icon: 'success',
        button : 'Cetak Tiket'
    }).then(()=> {
        $store.print.ticket($event.detail[0])
    })"
>
    <x-loading-screen wire:loading wire:target="searchIdentifierNumber"/>
    <x-loading-screen wire:loading wire:target="submit"/>
    <form class="space-y-6 px-1" wire:submit="submit">
        <x-select-input id="patientType" label="Jenis Pasien (BPJS/Umum)" wire:model.live="oldPatientForm.patient_type">
            <x-select-option value="1" selected>Pasien BPJS</x-select-option>
            <x-select-option value="0">Pasien Umum / Asuransi Swasta</x-select-option>
        </x-select-input>
        @if($oldPatientForm->patient_type === 1)
            <x-select-input id="typeOfVisit" label="Jenis Kunjungan" wire:model.live="oldPatientForm.type_of_visit">
                <x-select-option value="1">Rujukan FKTP</x-select-option>
                <x-select-option value="2">Rujukan Internal</x-select-option>
                <x-select-option value="3">Kontrol</x-select-option>
                <x-select-option value="4">Rujukan Antar RS</x-select-option>
            </x-select-input>
        @endif
        <div class="flex gap-4">
            <div class="w-full">
                <x-input
                    wire:model.live.300="oldPatientForm.identifier_number"
                    id="identifierNumber" type="number"
                    label="{{$oldPatientForm->patient_type === 1 ? 'Nomor BPJS/RM/NIK' : 'Nomor RM/NIK' }}"
                    placeholder="{{$oldPatientForm->patient_type === 1 ? 'Masukkan nomor BPJS/RM/NIK (Pilih salah satu)' : 'Masukkan nomor RM/NIK (Pilih salah satu)' }} "
                />
                <x-input-error for="oldPatientForm.identifier_number"/>
            </div>
            <x-button type="button" class="h-min mt-7" wire:click="searchIdentifierNumber" :disabled="$isButtonSearch">
                Cari
            </x-button>
        </div>

        @if(!is_null($selectedPatientData))
            <div class="my-4 p-4 rounded-xl bg-gray-50">
                @if($oldPatientForm->patient_type === 0)
                    <h6 class="mb-2 text-lg font-medium">Data Pribadi Pasien</h6>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <span class="block text-gray-500">Nama</span>
                            <p x-text="$wire.selectedPatientData?.nama ?? ''"
                               class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                        </div>
                        <div class="space-y-1">
                            <span class="block text-gray-500">Tanggal Lahir</span>
                            <p x-text="$wire.selectedPatientData?.tanggal_lahir ?? ''"
                               class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                        </div>
                    </div>
                @endif

                @if($oldPatientForm->patient_type === 1)
                    @switch($oldPatientForm->type_of_visit)
                        @case(2)
                            <h6 class="mb-2 text-lg font-medium">Data Pribadi Pasien</h6>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Nama</span>
                                    <p x-text="$wire.selectedPatientData?.nama ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Tanggal Lahir</span>
                                    <p x-text="$wire.selectedPatientData?.tanggal_lahir ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                            </div>
                            @break
                        @case(3)
                            <h6 class="mb-2 text-lg font-medium">Data Kontrol</h6>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Nomor Kontrol</span>
                                    <p x-text="$wire.selectedPatientData?.noSuratKontrol ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Tanggal Rencana Kontrol</span>
                                    <p x-text="$wire.selectedPatientData?.tglRencanaKontrol ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Nama Poli</span>
                                    <p x-text="$wire.selectedPatientData?.namaPoliTujuan ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                            </div>
                            @break
                        @default
                            <h6 class="mb-2 text-lg font-medium">Data Rujukan</h6>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Nomor Rujukan</span>
                                    <p x-text="$wire.selectedPatientData?.noKunjungan ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                                <div class="space-y-1">
                                    <span class="block text-gray-500">Nama Faskes Perujuk</span>
                                    <p x-text="$wire.selectedPatientData?.provPerujuk.nama ?? ''"
                                       class="px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg"></p>
                                </div>
                            </div>
                    @endswitch
                @endif
            </div>
        @endif
        <div>
            <x-button type="submit" :disabled="$this->selectedPatientData ? false : true">
                Daftar
            </x-button>
        </div>
    </form>
    <iframe id="print-frame" class="absolute w-0 h-0 border-[none] -left-[200vw] -top-[200vh]"></iframe>
    @if($BPJSModal)
        <x-large-modal wire:model.live="BPJSModal">
            <x-slot:title>
                <span class="text-xl font-medium">Rekam Medis Pasien</span>
            </x-slot:title>
            <x-slot:modal-content>
                @if(isset($response['response']) && $oldPatientForm->patient_type === 1)
                    <h4 class="text-xl font-medium mb-4 w-full">
                        {{$this->oldPatientForm->type_of_visit === 1 ? 'Rujukan Terakhir' :
                            ($this->oldPatientForm->type_of_visit === 3 ?
                            'Kontrol Terakhir' : '')}}
                    </h4>
                    <div class="flex flex-wrap gap-6 mb-4 bg-gray-50 p-4 rounded-xl mr-2">
                        <div>
                            <x-bpjs-card>
                                <div class="space-y-1">
                                    @switch($this->oldPatientForm->type_of_visit)
                                        @case(3)
                                            <p>No Kontrol</p>
                                            <p>Tanggal Terbit</p>
                                            <p>Tanggal Rencana Kontrol</p>
                                            <p>Poli Tujuan</p>
                                            <p>Dokter</p>
                                            @break
                                        @default
                                            <p>Nomor Kunjungan</p>
                                            <p>Jenis Kunjungan</p>
                                            <p>Nama Faskes Perujuk</p>
                                            <p>Tanggal Kunjungan</p>
                                    @endswitch
                                </div>
                                <div class="space-y-1">
                                    @switch($this->oldPatientForm->type_of_visit)
                                        @case(3)
                                            <p>: {{$response['response']['list'][0]['noSuratKontrol']}}</p>
                                            <p>: {{$response['response']['list'][0]['tglTerbitKontrol']}}</p>
                                            <p>: {{$response['response']['list'][0]['tglRencanaKontrol']}}</p>
                                            <p>: {{$response['response']['list'][0]['namaPoliTujuan']}}
                                                - {{$response['response']['list'][0]['poliTujuan']}}</p>
                                            <p>: {{$response['response']['list'][0]['namaDokter']}}</p>
                                            @break
                                        @default
                                            <p>: {{$response['response']['rujukan'][0]['noKunjungan']}}</p>
                                            <p>: {{$this->getTypeOfVisit($this->oldPatientForm->type_of_visit)}}</p>
                                            <p>: {{$response['response']['rujukan'][0]['provPerujuk']['nama']}}</p>
                                            <p>: {{$response['response']['rujukan'][0]['tglKunjungan']}}</p>
                                    @endswitch
                                </div>
                            </x-bpjs-card>
                            @if($this->oldPatientForm->type_of_visit === 3)
                                <x-button class="mt-4 w-full"
                                          wire:click="selectedRecord({{json_encode($response['response']['list'][0])}})">
                                    Pilih
                                </x-button>
                            @else
                                <x-button class="mt-4 w-full"
                                          wire:click="selectedRecord({{json_encode($response['response']['rujukan'][0])}})">
                                    Pilih
                                </x-button>
                            @endif

                        </div>
                    </div>
                    <div class="w-full h-0.5 bg-gray-100"></div>
                    <h4 class="text-xl font-medium mt-4 mb-2 w-full">
                        {{$this->oldPatientForm->type_of_visit === 1 ? 'Riwayat Rujukan Lainnya' :
                           ($this->oldPatientForm->type_of_visit === 3 ?
                           'Riwayat Kontrol' : '')}}
                    </h4>
                    <div class="flex flex-wrap gap-6 mb-2 bg-gray-50 rounded-xl p-4 mr-2">
                        @switch($this->oldPatientForm->type_of_visit)
                            @case(3)
                                @foreach($response['response']['list'] as $key => $controll)
                                    @if($key !== 0)
                                        <x-bpjs-card>
                                            <div class="space-y-1">
                                                <p>No Kontrol</p>
                                                <p>Tanggal Terbit</p>
                                                <p>Tanggal Rencana Kontrol</p>
                                                <p>Poli Tujuan</p>
                                                <p>Dokter</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p>: {{$controll['noSuratKontrol']}}</p>
                                                <p>: {{$controll['tglTerbitKontrol']}}</p>
                                                <p>: {{$controll['tglRencanaKontrol']}}</p>
                                                <p>: {{$controll['namaPoliTujuan']}} - {{$controll['poliTujuan']}}</p>
                                                <p>: {{$controll['namaDokter']}}</p>
                                            </div>
                                        </x-bpjs-card>
                                    @endif
                                    @if(count($response['response']['list']) === 1)
                                        <div>
                                            Tidak ada riwayat
                                        </div>
                                    @endif
                                @endforeach
                                @break
                            @default
                                @foreach($response['response']['rujukan'] as $key => $reference)
                                    @if($key !== 0)
                                        <x-bpjs-card>
                                            <div class="space-y-1">
                                                <p>Nomor Kunjungan</p>
                                                <p>Jenis Kunjungan</p>
                                                <p>Nama Faskes Perujuk</p>
                                                <p>Tanggal Kunjungan</p>
                                            </div>
                                            <div class="space-y-1">
                                                <p>: {{$reference['noKunjungan']}}</p>
                                                <p>: {{$this->getTypeOfVisit($this->oldPatientForm->type_of_visit)}}</p>
                                                <p>: {{$reference['provPerujuk']['nama']}}</p>
                                                <p>: {{$reference['tglKunjungan']}}</p>
                                            </div>
                                        </x-bpjs-card>
                                    @endif
                                    @if(count($response['response']['rujukan']) === 1)
                                        <div>
                                            Tidak ada riwayat
                                        </div>
                                    @endif
                                @endforeach
                        @endswitch
                    </div>
                @endif
            </x-slot:modal-content>
        </x-large-modal>
    @endif
</div>
