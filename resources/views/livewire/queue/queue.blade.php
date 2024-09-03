<div>
    <x-offline-alert/>
    @teleport('#toolbar')
        <div class="flex justify-between gap-4 items-center w-full">
            <div class="relative w-full">
                <input type="search"
                       wire:model.live.debounce.250ms="keyword"
                       placeholder="Cari poli"
                       class="border pl-12 pr-3 py-2 border-gray-300 w-full md:w-1/4 focus:outline-none focus:ring-red-500 focus:border-primary rounded-lg">
                <span class="ic-search absolute left-3 top-[8px] text-gray-400"></span>
            </div>
            <x-link variant="btn-outline" url="/antrean/cetak-ulang">
                <span class="ic-printer"></span>
                Cetak Ulang Tiket
            </x-link>
        </div>
    @endteleport

    <x-card-container>
        @forelse($polyclinics as $polyclinic)
            <x-solid-card-link link="/antrean/poliklinik/{{$polyclinic->kode}}">
                Poliklinik {{strtolower($polyclinic->nama)}}
            </x-solid-card-link>
        @empty
            <div class="grid-item-full h-[50vh] flex justify-center items-center flex-col p-6">
                <img src="{{asset('images/medical_care.svg')}}" alt="Medicine Image" class="w-full max-w-[320px]">
                <p class="text-gray-700 text-xl mt-8">Poliklinik tidak ditemukan</p>
            </div>
        @endforelse
    </x-card-container>

</div>
