<div class="mt-8"
     x-on:reprint-notification="swal({title: $event.detail[0].title, text: $event.detail[0].message})"
     x-on:reprint-success="$store.print.ticket($event.detail[0])">

    @teleport('#toolbar')
        <x-sub-header title="Cetak Ulang Tiket"/>
    @endteleport
    <form class="w-full px-1" wire:submit="submit">
        <div class="my-2">
            <x-input
                wire:model.live.300="identifierNumber"
                id="identifierNumber"
                type="number"
                label="Nomor BPJS/RM/NIK"
                placeholder="Masukkan nomor BPJS/RM/NIK (Pilih salah satu)"/>
            <x-input-error for="identifierNumber"/>
        </div>
        <div class="mt-4 flex justify-end">
            <x-button :disabled="!$this->isButtonSubmit">
                <span wire:loading.class="hidden" wire:target="submit">Cetak</span>
                <span wire:loading wire:target="submit">Loading ..</span>
            </x-button>
        </div>
    </form>
    <iframe id="print-frame" class="absolute w-0 h-0 border-[none] -left-[200vw] -top-[200vh]"></iframe>
</div>
