<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        <style>
            /* ── Auth Page — Light & Clean ── */
            html, body {
                height: 100%;
                margin: 0;
            }

            .auth-root {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                /* Background terang dengan gradient subtle berbasis teal */
                background-color: #f0fdfa;
                background-image:
                    radial-gradient(ellipse 70% 50% at 50% 0%,   rgba(20, 184, 166, 0.15), transparent),
                    radial-gradient(ellipse 50% 40% at 100% 100%, rgba(15, 118, 110, 0.10), transparent),
                    radial-gradient(ellipse 40% 30% at 0% 100%,   rgba(94, 234, 212, 0.12), transparent);
            }

            .auth-card {
                width: 100%;
                max-width: 420px;
                background: #ffffff;
                border-radius: 20px;
                box-shadow:
                    0 1px 3px rgba(0,0,0,0.05),
                    0 8px 32px rgba(15, 118, 110, 0.10),
                    0 24px 64px rgba(15, 118, 110, 0.06);
                overflow: hidden;
            }

            .auth-card-inner {
                padding: 40px 40px 36px;
            }

            /* Input styles */
            .auth-input {
                width: 100%;
                padding: 10px 14px;
                border-radius: 10px;
                border: 1.5px solid #e2e8f0;
                background: #f8fafc;
                font-size: 14px;
                color: #1e293b;
                outline: none;
                transition: all 0.15s ease;
                box-sizing: border-box;
                font-family: inherit;
            }

            .auth-input::placeholder {
                color: #94a3b8;
            }

            .auth-input:focus {
                border-color: #0F766E;
                background: #ffffff;
                box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
            }

            .auth-label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: #374151;
                margin-bottom: 6px;
                letter-spacing: 0.01em;
            }

            .auth-btn {
                width: 100%;
                padding: 11px 20px;
                border-radius: 10px;
                border: none;
                cursor: pointer;
                font-size: 14px;
                font-weight: 600;
                color: #ffffff;
                background: linear-gradient(135deg, #0F766E, #14B8A6);
                box-shadow: 0 4px 12px rgba(15, 118, 110, 0.30);
                transition: all 0.2s ease;
                font-family: inherit;
                letter-spacing: 0.01em;
            }

            .auth-btn:hover {
                background: linear-gradient(135deg, #0d6560, #0F766E);
                box-shadow: 0 6px 20px rgba(15, 118, 110, 0.40);
                transform: translateY(-1px);
            }

            .auth-btn:active {
                transform: translateY(0);
                box-shadow: 0 2px 8px rgba(15, 118, 110, 0.25);
            }

            .auth-divider {
                height: 1px;
                background: #f1f5f9;
                margin: 24px 0;
            }

            .error-msg {
                font-size: 12px;
                color: #dc2626;
                margin-top: 4px;
            }

            .toggle-pw {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #94a3b8;
                padding: 4px;
                display: flex;
                align-items: center;
                transition: color 0.15s;
            }

            .toggle-pw:hover {
                color: #64748b;
            }

            .remember-label {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: #64748b;
                cursor: pointer;
                user-select: none;
            }

            .remember-label input[type="checkbox"] {
                width: 15px;
                height: 15px;
                border-radius: 4px;
                accent-color: #0F766E;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="auth-root">
            <div class="auth-card">
                <div class="auth-card-inner">

                    {{-- ── Logo & Brand ── --}}
                    {{-- Logo ada di: public/images/logo.png — ganti file tersebut untuk update logo --}}
                    <div style="display:flex; flex-direction:column; align-items:center; margin-bottom:28px;">
                        <a href="{{ route('home') }}" wire:navigate>
                            <img src="{{ asset('images/logo.png') }}"
                                 alt="Logo Gusti Putra"
                                 style="height: 64px; width: auto; object-fit: contain;" />
                        </a>
                        <p style="font-size:12px; color:#94a3b8; margin-top:6px; letter-spacing:0.05em; text-transform:uppercase;">
                            Pengepul Kertas Bekas
                        </p>
                    </div>

                    {{-- ── Title ── --}}
                    <div style="text-align:center; margin-bottom:24px;">
                        <h1 style="font-size:20px; font-weight:700; color:#0f172a; margin:0 0 4px;">
                            Masuk ke Sistem
                        </h1>
                        <p style="font-size:13px; color:#94a3b8; margin:0;">
                            Masukkan kredensial Anda untuk melanjutkan
                        </p>
                    </div>

                    {{-- ── Session Status ── --}}
                    <x-auth-session-status class="mb-4 text-sm" :status="session('status')" />

                    {{-- ── Form ── --}}
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

                </div>

                {{-- ── Footer strip ── --}}
                <div style="background:#f8fafc; border-top:1px solid #f1f5f9; padding:14px 40px; text-align:center;">
                    <p style="font-size:12px; color:#cbd5e1; margin:0;">
                        &copy; {{ date('Y') }} Gusti Putra &middot; Sistem Internal
                    </p>
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts

        <script>
            function togglePw() {
                const field = document.getElementById('password');
                const eye   = document.getElementById('icon-eye');
                const eyeOff = document.getElementById('icon-eye-off');
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
    </body>
</html>
