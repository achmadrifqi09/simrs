<div>
    @teleport('#actionDetailHeader')
        <x-button wire:click="handleModal">
            <div wire:loading wire:target="handleModal">
                <x-spinner/>
            </div>
            <span>Tambah Jadwal</span>
        </x-button>
    @endteleport

    <div>
        {{ $this->table }}
    </div>

    <x-modal id="polyclinicModal" wire:model.live="scheduleModal">
        <x-slot:title>{{$scheduleModalTitle}}</x-slot:title>
        <x-slot:modal-content>
            <form wire:submit="submit">
                <div class="my-6 relative">
                    <x-input type="text" placeholder="Ketikkan nama poli untuk mencari.." id="polyCode"
                             wire:model.live.debounce.350="form.poly_code" label="Kode Poliklinik"/>
                    <x-input-error for="form.poly_code"/>
                    @if($polyclinics)
                        <div
                            class="absolute top-[78px] bg-white shadow-md border w-full rounded-lg border-gray-100 z-10 max-h-40 overflow-y-auto">
                            @forelse($polyclinics as $polyclinic)
                                <button type="button" class="text-left text-sm py-2 hover:bg-red-50 py-2.5 px-3 w-full"
                                        wire:click="selectPoly('{{$polyclinic->kode}}')">{{$polyclinic->nama}}</button>
                            @empty
                                <p class="text-gray-500 text-sm py-2.5 px-3">Tidak ada data poli yang cocok
                                    dengan {{$form->poly_code}}</p>
                            @endforelse
                        </div>
                    @endif
                </div>
                <div class="my-6 flex justify-between gap-4">
                    <div class="w-full">
                        <x-select-input id="day" wire:model.blur="form.day" label="Hari">
                            <x-select-option value="1">Senin</x-select-option>
                            <x-select-option value="2">Selasa</x-select-option>
                            <x-select-option value="3">Rabu</x-select-option>
                            <x-select-option value="4">Kamis</x-select-option>
                            <x-select-option value="5">Jum'at</x-select-option>
                            <x-select-option value="6">Sabtu</x-select-option>
                            <x-select-option value="7">Minggu</x-select-option>
                        </x-select-input>
                        <x-input-error for="form.day"/>
                    </div>
                    <div class="w-full">
                        <x-select-input id="holidayStatus" wire:model.blur="form.holiday" label="Status Libur">
                            <x-select-option value="0">Tidak</x-select-option>
                            <x-select-option value="1">Ya</x-select-option>
                        </x-select-input>
                    </div>
                </div>

                <div class="my-6 flex justify-between gap-4">
                    <div class="w-full">
                        <x-input type="number" id="BPJSCapacity" wire:model.blur="form.bpjs_capacity"
                                 label="Kapasitas Pasien BPJS"/>
                        <x-input-error for="form.bpjs_capacity"/>
                    </div>
                    <div class="w-full">
                        <x-input type="number" id="nonBPJSCapacity" wire:model.blur="form.non_bpjs_capacity"
                                 label="Kapasitas Pasien Umum"/>
                        <x-input-error for="form.non_bpjs_capacity"/>
                    </div>
                </div>
                <div class="my-6 flex justify-between gap-4">
                    <div class="w-full">
                        <x-input type="time" id="openPracticeHour" wire:model.blur="form.open_practice_hour"
                                 step="1"
                                 label="Jam Praktek Buka"/>
                        <x-input-error for="form.open_practice_hour"/>
                    </div>
                    <div class="w-full">
                        <x-input type="time" id="closePracticeHour" wire:model.blur="form.close_practice_hour"
                                 step="1"
                                 label="Jam Praktek Tutup"/>
                        <x-input-error for="form.close_practice_hour"/>
                    </div>
                </div>
                <x-input-error for="schedule-action-status" class="w-full text-end mb-4"/>
                <div class="flex justify-end items-center">
                    <x-button :disabled="$errors->any()" wire:loading.attr="disabled" wire:target="submit">
                        <div wire:loading wire:target="submit">
                            <x-spinner/>
                        </div>
                        <span>Simpan</span>
                    </x-button>
                </div>
            </form>
        </x-slot:modal-content>
    </x-modal>
</div>
