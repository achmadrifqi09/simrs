<div
    x-cloak
    x-show="isMenuBar"
    :class="isMenuBar ? 'bg-white flex-1 min-w-[21em] bg-dark ml-0 sidebar-menu text-sm pl-6 pr-6 py-6 overflow-y-auto p-0' :
        'bg-white h-dvh min-w-[21em] bg-dark -ml-[21em] sidebar-menu text-sm pl-6 pr-3 py-6 overflow-y-scroll p-0'"
    x-data="{selectedItem : null}">
    <x-link url="/" variant="btn-ghost" class="gap-4">
        <img src="{{ asset('images/logo-rs.webp') }}" alt="Logo RSU UMM" class="w-16">
        <div>
            <h3 class="text-2xl font-bold">RSU UMM</h3>
            <span class="text-sm text-gray-500">Layananku Pengabdianku</span>
        </div>
    </x-link>

    <x-menus>
        <x-menu url="/" :active="request()->routeIs('dashboard')">
           <div class="flex items-center gap-4">
               <span class="ic-widget"></span>
               <span>Dashboard</span>
           </div>
        </x-menu>

        <x-menu-dropdown id="queueDropdown" :active="str_contains(request()->route()->uri(), 'antrean')">
            <span class="ic-user-group"></span>
            <span>Antrean</span>
            <x-slot:menu-dropdown-items>
                <x-menu-dropdown-item url="/antrean">Ambil Antrean</x-menu-dropdown-item>
                <x-menu-dropdown-item url="/antrean/monitoring">Monitoring Antrean</x-menu-dropdown-item>
                <x-menu-dropdown-item url="#">Daftar Antrean</x-menu-dropdown-item>
            </x-slot:menu-dropdown-items>
        </x-menu-dropdown>

        <x-menu-dropdown id="referenceDropdown" :active="str_contains(request()->route()->uri(), 'referensi')">
            <span class="ic-docs"></span>
            <span>Referensi</span>
            <x-slot:menu-dropdown-items>
                <x-menu-dropdown-item url="/referensi/dokter">Dokter</x-menu-dropdown-item>
                <x-menu-dropdown-item url="/referensi/poliklinik">
                    Poliklinik
                </x-menu-dropdown-item>
            </x-slot:menu-dropdown-items>
        </x-menu-dropdown>

        <x-menu url="/sep" :active="request()->routeIs('sep')">
            <div class="flex items-center gap-4">
                <span class="ic-card-2-linear"></span>
                <span>SEP</span>
            </div>
        </x-menu>


        <x-menu-dropdown id="masterDropdown" :active="str_contains(request()->route()->uri(), 'master')">
            <span class="ic-server"></span>
            <span>Master</span>
            <x-slot:menu-dropdown-items>
                <x-menu-dropdown-item url="#">Pasien</x-menu-dropdown-item>

            </x-slot:menu-dropdown-items>
        </x-menu-dropdown>

    </x-menus>


</div>
