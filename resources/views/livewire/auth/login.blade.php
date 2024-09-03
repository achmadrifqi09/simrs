<div class="flex justify-center items-center h-dvh flex-col gap-6">
    <div class="flex gap-6 items-center">
        <img src="{{asset('images/logo-rs.webp')}}" alt="Logo RS UMM" class="w-28">
        <h3 class="text-xl font-bold">Rumah Sakit Umum<br>Universitas Muhammadiyah<br>Malang</h3>
    </div>
    <div class="mx-6 p-6 min-w-[396px] w-full md:w-1/3 lg:w-1/4">
        <h2 class="text-2xl font-medium">Login</h2>
        <form wire:submit="authenticate">
            <div class="space-y-4 mt-6">
                <div>
                    <x-input id="username" label="Username" wire:model.live.300="form.username"/>
                    <x-input-error for="form.username"/>
                </div>
                <div>
                    <x-input id="passwors" label="Password" type="password" wire:model.live.300="form.password"/>
                    <x-input-error for="form.password"/>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" wire:model.blur="form.remember" class="w-4 h-4">
                    <label>Remember Me</label>
                </div>
                <x-input-error for="login-status"/>
                <div class="p flex justify-end items-center">
                    <x-button class="mt-4" type="submit" wire:loading.attr="disabled" wire:target="authenticate">
                        <span wire:loading.class="hidden" wire:target="authenticate">Login</span>
                        <span wire:loading wire:target="authenticate">Loading ..</span>
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
