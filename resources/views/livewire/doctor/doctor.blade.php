<div class="space-y-6">
    <x-header>
        <x-slot:title>Referensi Data Dokter</x-slot:title>
    </x-header>
    <div>

        <x-widget-container>
            <x-stat-widget>
                <x-slot:icon>
                    <span class="w-8 h-8 ic-user-group"></span>
                </x-slot:icon>
                <x-slot:value>{{$statistics->total_dokter}}</x-slot:value>
                <x-slot:description>Total keseluruhan dokter</x-slot:description>
            </x-stat-widget>

            <x-stat-widget>
                <x-slot:icon>
                    <span class="w-8 h-8 ic-stethoscope"></span>
                </x-slot:icon>
                <x-slot:value>{{$statistics->total_dokter_praktek}}</x-slot:value>
                <x-slot:description>Total dokter praktek hari ini</x-slot:description>
            </x-stat-widget>

            <x-stat-widget>
                <x-slot:icon>
                    <span class="w-8 h-8 ic-calendar"></span>
                </x-slot:icon>
                <x-slot:value>{{$statistics->total_dokter_libur}}</x-slot:value>
                <x-slot:description>Total dokter libur hari ini</x-slot:description>
            </x-stat-widget>
        </x-widget-container>
    </div>
    <x-section class="text-sm">
        <livewire:doctor.doctor-table/>
    </x-section>
</div>
