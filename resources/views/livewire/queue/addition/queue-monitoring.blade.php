<div>
    <x-header>
        <x-slot:title>Monitoring Antrean BPJS</x-slot:title>
    </x-header>
    <div class="py-2">
        <h4 class="text-md text-lg mb-2.5 font-medium">Ringkasan anteran {{$currentDate}} (Perhari)</h4>
        <x-widget-container>
            <x-stat-widget>
                <x-slot:icon>
                    <span class="w-8 h-8 ic-stethoscope"></span>
                </x-slot:icon>
                <x-slot:value>0</x-slot:value>
                <x-slot:description>Keseluruhan antrean</x-slot:description>
            </x-stat-widget>

            <x-stat-widget>
                <x-slot:icon>
                    <span class="w-8 h-8 ic-stethoscope"></span>
                </x-slot:icon>
                <x-slot:value>0</x-slot:value>
                <x-slot:description>Antrean gagal terkirim</x-slot:description>
            </x-stat-widget>

            <x-stat-widget>
                <x-slot:icon>
                    <span class="w-8 h-8 ic-stethoscope"></span>
                </x-slot:icon>
                <x-slot:value>0</x-slot:value>
                <x-slot:description>Antrean  berhasil terkirim</x-slot:description>
            </x-stat-widget>

        </x-widget-container>
    </div>

    <x-section class="text-sm">
        <h4 class="text-md font-medium text-lg mb-2.5">Daftar Log Antrean Masuk</h4>
        <i>*Status terakhir <b>selalu terupdate</b> saat sistem mengirimkan <b>Task ID</b> ke WS BPJS</i>
        <livewire:queue.addition.queue-monitoring-table/>
    </x-section>
</div>
