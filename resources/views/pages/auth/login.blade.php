<x-layouts::auth :title="__('Log in')">

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4 text-sm" :status="session('status')" />

    {{-- Form --}}
    <form method="POST" action="{{ route('login.store') }}" style="display:flex; flex-direction:column; gap:16px;">
        @csrf

        {{-- Username --}}
        <div>
            <label class="auth-label" for="name">Nama Pengguna</label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="Contoh: QC / Penimbang / Kasir"
                class="auth-input"
            />
            @error('name')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="auth-label" for="password">Password</label>
            <div style="position:relative;">
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="auth-input"
                    style="padding-right: 44px;"
                />
                <button type="button" class="toggle-pw" onclick="togglePw()" tabindex="-1" aria-label="Tampilkan password">
                    <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <label class="remember-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
            Ingat saya di perangkat ini
        </label>

        {{-- Submit --}}
        <button type="submit" class="auth-btn" data-test="login-button" style="margin-top:4px;">
            Masuk
        </button>
    </form>

    <script>
        function togglePw() {
            const field   = document.getElementById('password');
            const eye     = document.getElementById('icon-eye');
            const eyeOff  = document.getElementById('icon-eye-off');
            if (field.type === 'password') {
                field.type = 'text';
                eye.style.display = 'none';
                eyeOff.style.display = 'block';
            } else {
                field.type = 'password';
                eye.style.display = 'block';
                eyeOff.style.display = 'none';
            }
        }
    </script>

</x-layouts::auth>