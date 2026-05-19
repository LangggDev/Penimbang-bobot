<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header 
            :title="__('Login Sistem')" 
            :description="__('Masukkan nama pengguna dan password untuk masuk')" 
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Username / Name -->
            <flux:input
                name="name"
                :label="__('Nama Pengguna')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="username"
                placeholder="Contoh: QC / Penimbang / Kasir"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Ingat saya')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>