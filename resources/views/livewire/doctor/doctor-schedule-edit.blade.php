<div>
    <x-header slug="Edit">
        <x-slot:title>Edit Dokter</x-slot:title>
    </x-header>
    <div class="mb-6 flex gap-4" id="actionDetailHeader">
        <x-button variant="btn-outline" x-on:click="window.history.back();">
            Kembali
        </x-button>
    </div>
    <x-section class="mb-6">
        <h5 class="text-lg mb-6">Form Edit Dokter</h5>
        <form wire:submit="submit">
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                   <x-input id="doctorName" label="Nama Dokter" wire:model.blur="form.name"/>
                    <x-input-error for="form.name"/>
                </div>
                <div>
                    <x-input id="doctorName" label="Kode Dokter" wire:model.blur="form.code" type="number"/>
                    <x-input-error for="form.code"/>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <x-button wire:loading.attr="disabled" wire:target="submit">
                    <span wire:loading.class="hidden" wire:target="submit">Simpan</span>
                    <span wire:loading wire:target="submit">Loading ..</span>
                </x-button>
            </div>
        </form>
    </x-section>

    <x-section>
        <livewire:doctor.doctor-schedule-table :doctor="$doctor"/>
    </x-section>
</div>
