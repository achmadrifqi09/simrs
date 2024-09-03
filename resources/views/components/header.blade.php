@props(['btnBack' => false, 'title', 'slug'])
<header class="mb-6 flex justify-between">
    <div class="w-full">
        <x-breadcrumb :slug="$slug ?? null"/>
        <div class="flex justify-between w-full">
            <h2 class="text-2xl font-semibold">{{$title}}</h2>
            @if($btnBack)
                <x-button variant="btn-outline" x-on:click="window.history.back();">
                    Kembali
                </x-button>
            @endif
        </div>
    </div>
</header>
