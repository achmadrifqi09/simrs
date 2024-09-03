@php use Carbon\Carbon; @endphp

<div>
    @teleport('#toolbar')
        <x-sub-header :title="'Jadwal Praktek Poliklinik '. $polyclinic->nama"/>
    @endteleport

    <x-offline-alert/>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mx-3 py-4">

        @forelse($schedules as $key => $schedule)
            @if($polyclinic->kode === 'IRM' && $key == 0)
                <livewire:queue.addition.physiotherapy-card/>
            @endif
            <x-practice-schedule-card>
                <x-slot:title>
                    <div class="h-10 w-10 flex items-center justify-center bg-primary border-4 border-red-200 rounded-full">
                        <span class="ic-stethoscope block text-white"></span>
                    </div>
                    {{strtolower($schedule->nama)}}

                    @if($schedule->jadwal_sekarang[0]['libur'] == 1)
                        <span class=" top-6 right-6 text-base font-medium bg-primary px-2 rounded-md text-white">Libur</span>
                    @endif
                </x-slot:title>
                <x-slot:content>
                    <div class="w-full">
                        <h6 class="mb-3">Jadwal praktek hari ini</h6>
                        <div class="flex w-full justify-between gap-6 flex-col md:flex-row">
                            @foreach($schedule->jadwal_sekarang as $practiceKey => $currentPractice)
                                <div class="w-full">
                                    <p class="font-medium">
                                        {{count($schedule->jadwal_sekarang) > 1 && $practiceKey == 1 ? 'Praktek Kedua' : 'Praktek Pertama'}}
                                    </p>
                                    <div class="mt-2 flex gap-2 items-center mb-1.5">
                                        <p class="text-gray-600">
                                            {{$currentPractice['jam_praktek_buka']}} -
                                            {{$currentPractice['jam_praktek_tutup']}}
                                        </p>
                                    </div>
                                    <div class="mb-1.5">
                                        <p class="text-gray-600">Sisa antrean {{$currentPractice['total_antrian']}}
                                            /{{$currentPractice['kapasitas_pasien']}}</p>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-1 overflow-x-hidden">
                                            <div class="bg-primary h-2.5 rounded-full"
                                                 style="width: {{((int) $currentPractice['total_antrian'] / (int) $currentPractice['kapasitas_pasien']) * 100}}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($practiceKey == 0)
                                    <div class="h-24 w-0.5 bg-gray-300 hidden md:block"></div>
                                @endif
                                @if(count($schedule->jadwal_sekarang) == 1)
                                    <div class="w-full">
                                        <p class="font-medium">Praktek Kedua</p>
                                        <p class="text-gray-600">Jadwal praktek kedua tidak tersedia</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="flex gap-4 mt-4">
                            <x-button
                                :disabled="!$this->registrationAvailability(json_encode($schedule->jadwal_sekarang)) || $schedule->jadwal_sekarang[0]['libur'] == 1"
                                wire:click="selectDoctor('{{$schedule->kode}}', '{{strtolower($schedule->nama)}}', {{json_encode($schedule->jadwal_sekarang)}})">
                                {{$this->registrationAvailability(json_encode($schedule->jadwal_sekarang)) ? 'Ambil Antrean' : 'Tutup'}}
                            </x-button>
                            <x-button
                                wire:click="doctorPracticeSchedule('{{json_encode($schedule->jadwal_praktek)}}', '{{strtolower($schedule->nama)}}')"
                                variant="btn-outline">
                                Lihat Jadwal
                            </x-button>
                        </div>
                    </div>
                </x-slot:content>
            </x-practice-schedule-card>
        @empty
            @if($polyclinic->kode === 'IRM')
                <livewire:queue.addition.physiotherapy-card/>
            @endif
            @if($polyclinic->kode !== 'IRM')
                <div
                    class="w-full bg-opacity-60 backdrop-blur-sm col-span-1-1 md:col-span-2 lg:col-span-3 xl:col-span-4 flex flex-col gap-8 justify-center items-center h-full py-16">
                    <img src="{{asset('images/medicine.svg')}}" alt="Medicine Image" class="w-full max-w-[360px]">
                    <p class="text-gray-700">Mohon maaf tidak ada jadwal praktek dokter di poli <span
                            class="capitalize">{{strtolower($polyclinic->nama)}}</span> hari ini</p>
                </div>
            @endif
        @endforelse
    </div>
    @if($doctorSchedules)
        <x-drawer wire:model.live="scheduleDrawer">
            <x-slot:title>
                Jadwal Praktek {{$doctorSchedules['doctor_name']}}
            </x-slot:title>
            <x-slot:drawer-content>
                <div class="flex flex-wrap gap-6">
                    @foreach($doctorSchedules['schedules'] as $schedules)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @foreach($schedules as $practiceKey => $practice)
                                @if($practiceKey == 0)
                                    <div class="mb-2 flex items-center justify-between">
                                        <p class="font-medium">{{$this->getDayName($practice['hari_praktek'])}}</p>
                                        @if($practice['hari_praktek'] == Carbon::now()->dayOfWeekIso)
                                            <span
                                                class="block font-medium text-sm bg-primary py-0.5 px-1 text-white rounded-md">Sekarang</span>
                                        @endif
                                    </div>
                                @endif
                                <div class="flex gap-2 items-center">
                                    <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                                    <p class="text-gray-600">{{$practice['jam_praktek_buka']}}
                                        - {{$practice['jam_praktek_tutup']}}</p>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </x-slot:drawer-content>
        </x-drawer>
    @endif
    <x-modal wire:model.live="openModal">
        <x-slot:title>
            Pilih Jam Praktek Dokter {{$this->selectedDoctor['doctor_name'] ?? ''}}
        </x-slot:title>
        <x-slot:modal-content>
            @if($selectedDoctor)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($selectedDoctor['schedules'] as $schedule)
                        @if($this->validationRegistrationModal($schedule['jam_praktek_tutup'], $schedule['total_antrian'] ?? 0, $schedule['kapasitas_pasien']))
                            <x-solid-card-link
                                link="/antrean/poliklinik/{{$polyCode}}/dokter/{{$selectedDoctor['doctor_code']}}?kunjungan=reguler&jam_praktek_buka={{$schedule['jam_praktek_buka']}}&jam_praktek_tutup={{$schedule['jam_praktek_tutup']}}">
                                {{$schedule['jam_praktek_buka']}} - {{$schedule['jam_praktek_tutup']}}
                            </x-solid-card-link>
                        @else
                            <x-solid-card-button disabled>
                                Tutup
                            </x-solid-card-button>
                        @endif
                    @endforeach
                </div>
            @endif
        </x-slot:modal-content>
    </x-modal>
</div>
