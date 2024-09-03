@props(['title' => 'toolbar', 'description' => ''])
<div class="flex justify-center items-center py-6 border-b-2 border-b-gray-200 w-full bg-white relative">
    <button x-on:click="window.history.back();" class="bg-white hover:bg-gray-50 block absolute left-0 text-primary border border-primary px-4 py-2 rounded-lg">
        <span class="hidden md:block">Kembali</span>
        <span class="md:hidden ic-chevron-left block"></span>
    </button>
    <div>
        <h4 class="capitalize max-w-[20ch] md:max-w-max md:text-xl font-semibold text-center text-gray-900">{{strtolower($title)}}</h4>
        <p class="text-gray-500 mt-1 text-center">{{$description ?? ""}}</p>
    </div>
</div>
