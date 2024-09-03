@php use Carbon\Carbon; @endphp
<div>
    <x-practice-schedule-card>
        <x-slot:title>
            Khusus Fisioterapi
        </x-slot:title>
        <x-slot:content>
            <div class="w-full">
                <h6 class="mb-3">Jadwal Terapi</h6>
                <div class="flex w-full justify-between gap-6 mb-[26px]">
                    <div class="w-full">
                        <p class="font-medium">
                            Hari
                        </p>
                        <div class="mt-2 flex gap-2 items-center mb-1.5">
                            <p class="text-gray-600">Senin - Sabtu</p>
                        </div>
                    </div>
                    <div class="h-24 w-0.5 bg-gray-300"></div>
                    <div class="w-full">
                        <p class="font-medium">
                            Jam Buka
                        </p>
                        <div class="mt-2 flex gap-2 items-center mb-1.5">
                            <p class="text-gray-600">07:00:00 - 17:00:00</p>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4 mt-4">
                    <x-button :disabled="Carbon::now()->greaterThan('23:00:00')" wire:click="handleOpenModal">
                        {{Carbon::now()->greaterThan('17:00:00') ? 'Tutup' : 'Ambil Antrean'}}
                    </x-button>
                </div>
            </div>
        </x-slot:content>
    </x-practice-schedule-card>
    <x-modal wire:model.live="physiotherapyModal">
        <x-slot:title>
            Pilih dokter yang merekomendasikan
        </x-slot:title>
        <x-slot:modal-content>
            @if($physiotherapyDoctors)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($physiotherapyDoctors as $doctor)
                        <x-solid-card-link
                            class="{{count($physiotherapyDoctors) == 1 ? 'w-max' : 'w-auto'}}"
                            link="/antrean/poliklinik/IRM/dokter/{{$doctor->kode_dokter}}?kunjungan=khusus&jam_praktek_buka=07:00:00&jam_praktek_tutup=17:00:00">
                            {{$doctor->nama}}
                        </x-solid-card-link>
                    @endforeach
                </div>
            @endif
        </x-slot:modal-content>
    </x-modal>
</div>
