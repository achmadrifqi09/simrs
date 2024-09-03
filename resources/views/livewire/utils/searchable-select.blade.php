<div class="relative h-[62px]">
    <div class="absolute w-full relative">
        <label for="{{$id}}" class="block mb-1 text-gray-500">Label</label>
        <input wire:model.live.debounce.300="keyword" placeholder="{{$placeholder}}" id="{{$id}}" type="text"
               class="border px-3 py-2 border-gray-300 w-full focus:outline-none focus:ring-primary focus:border-primary rounded-lg">
        <div class="w-full max-h-[120px] overflow-y-auto">
            <div class="w-full bg-white border border-gray-100 absolute z-10 top-[72px] rounded-lg w-full max-h-40 overflow-y-auto shadow" wire:loading wire:target="keyword">
                <div class="px-3 py-4 flex items-center gap-2" >
                    <p class="text-gray-500 text-center w-full">Loading ...</p>
                </div>
            </div>
            @if($results)
                <div
                    class="w-full bg-white border border-gray-100 absolute z-10 top-[72px] rounded-lg w-full max-h-40 overflow-y-auto shadow">
                    @forelse($results as $result)
                        <button class="px-3 py-2.5 w-full text-left hover:bg-red-50" wire:click="selected('{{$result[$valueKey]}}')">{{$result[$labelKey]}}</button>
                    @empty
                        <div class="px-3 py-4 flex items-center gap-2">
                            <p class="text-gray-500 text-left">Tidak ada data yang cocok dengan <span class="text-primary">{{$keyword}}</span></p>
                            <button class="text-white bg-primary rounded px-2 text-center" wire:click="clearSearch">Batal</button>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>

    <div class="mt-2">
        <select class="w-0 h-0 -z-[1] mt-10 opacity-0 hidden">
            <option value="">Test</option>
        </select>
    </div>
</div>
