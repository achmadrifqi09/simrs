<div>
    <div class="flex gap-4 flex-wrap">
        <x-button wire:click="handleModalCreate">
            <div wire:loading wire:target="handleModalCreate">
                <x-spinner/>
            </div>
            <span>Tambah Dokter</span>
        </x-button>
        <a href="/referensi/dokter/jadwal-bulanan" target="_blank" class="btn-outline">
            Jadwal Bulanan
        </a>
    </div>

    {{ $this->table }}

    <x-modal id="doctorModal" wire:model.live="doctorModal">
        <x-slot:title>
            {{$doctorModalTitle}}
        </x-slot:title>
        <x-slot:modal-content>
            <form wire:submit="submit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input id="doctorName" label="Nama Dokter" wire:model.blur="form.name" required/>
                        <x-input-error for="form.name"/>
                    </div>
                    <div>
                        <x-input id="doctorName" label="Kode Dokter" wire:model.blur="form.code" type="number" required/>
                        <x-input-error for="form.code"/>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
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
