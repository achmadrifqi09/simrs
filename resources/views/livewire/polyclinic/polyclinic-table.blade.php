<div>
    @teleport('#polyButtonWrapper')
        <x-button wire:click="openCreateModal">
            <div wire:loading wire:target="openCreateModal">
                <x-spinner/>
            </div>
            <span>Tambah Poliklinik</span>
        </x-button>
    @endteleport

    {{ $this->table }}

    <x-modal id="polyclinicModal" wire:model.live="polyclinicModal">
        <x-slot:title>{{{$polyclinicModalTitle}}}</x-slot:title>
        <x-slot:modal-content>
            <form wire:submit="submit">
                <div>
                    <div class="my-4">
                        <x-input id="polyName" wire:model.blur="form.name" label="Nama Poliklinik" required/>
                        <x-input-error for="form.name"/>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4 my-4">
                        <div>
                            <x-input id="polyCode" wire:model.blur="form.code" label="Kode Poliklinik" required/>
                            <x-input-error for="form.code"/>
                        </div>
                        <div>
                            <x-select-input id="polyStatus" wire:model.blur="form.status" label="Status Display Antrean" required>
                                <x-select-option value="0">Non Aktif</x-select-option>
                                <x-select-option value="1">Aktif</x-select-option>
                            </x-select-input>
                            <x-input-error for="form.status"/>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <x-button wire:loading.attr="disabled" wire:target="submit">
                        <div wire:loading wire:target="submit">
                            <x-spinner/>
                        </div>
                        <span>Simpan</span>
                    </x-button>
                </div>
            </form>
        </x-slot:modal-content>
    </x-modal>
</div>
