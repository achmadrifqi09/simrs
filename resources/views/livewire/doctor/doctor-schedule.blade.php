<div>
    <x-header>
        <x-slot:title>Jadwal Dokter</x-slot:title>
    </x-header>
    <div class="mb-6 flex gap-4" id="actionDetailHeader">
        <x-button variant="btn-outline" x-on:click="window.history.back();">
            Kembali
        </x-button>
    </div>
    <x-section class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="space-y-2">
                <p class="text-gray-500 text-sm">Nama Dokter</p>
                <h4 class="capitalize text-lg font-semibold">{{strtolower($doctor->nama)}}</h4>
            </div>
            <div class="space-y-2">
                <p class="text-gray-500 text-sm">Kode Dokter</p>
                <h4 class="capitalize text-lg font-semibold">{{strtolower($doctor->kode)}}</h4>
            </div>
            <div class="space-y-2">
                <p class="text-gray-500 text-sm">Nama Poliklinik</p>
                <h4 class="capitalize text-lg font-semibold">{{strtolower($doctor->schedules?->polyclinic->nama ?? '-')}}</h4>
            </div>
        </div>
    </x-section>
    <x-section>
        <livewire:doctor.doctor-schedule-table :doctor="$doctor"/>
    </x-section>
</div>
