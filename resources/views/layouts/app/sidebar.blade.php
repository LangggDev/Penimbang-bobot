<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:navlist variant="outline">
                    @if (auth()->user()->role === 'penimbang')
                        <flux:navlist.group :heading="__('Menu Penimbang')" class="grid">
                            <flux:navlist.item
                                icon="home"
                                :href="route('penimbang.dashboard')"
                                :current="request()->routeIs('penimbang.dashboard')"
                                wire:navigate
                            >
                                Dashboard
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="users"
                                :href="route('penimbang.pelanggan.index')"
                                :current="request()->routeIs('penimbang.pelanggan.*')"
                                wire:navigate
                            >
                                Data Pelanggan
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="clipboard-document-list"
                                :href="route('penimbang.transaksi.index')"
                                :current="request()->routeIs('penimbang.transaksi.*')"
                                wire:navigate
                            >
                                Transaksi Penimbangan
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="clock"
                                href="#"
                                wire:navigate
                            >
                                Riwayat Penimbangan
                            </flux:navlist.item>
                        </flux:navlist.group>
                    @endif

                    @if (auth()->user()->role === 'qc')
                        <flux:navlist.group :heading="__('Menu QC')" class="grid">
                            <flux:navlist.item
                                icon="home"
                                :href="route('qc.dashboard')"
                                :current="request()->routeIs('qc.dashboard')"
                                wire:navigate
                            >
                                Dashboard
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="check-badge"
                                :href="route('qc.penilaian.index')"
                                :current="request()->routeIs('qc.penilaian.*')"
                                wire:navigate
                            >
                                Penilaian QC
                            </flux:navlist.item>

                            <flux:navlist.item
                            icon="clock"
                            :href="route('qc.riwayat.index')"
                            :current="request()->routeIs('qc.riwayat.*')"
                            wire:navigate
                        >
                            Riwayat QC
                        </flux:navlist.item>

                        <flux:navlist.item
                                icon="calculator"
                                :href="route('qc.fuzzy.index')"
                                :current="request()->routeIs('qc.fuzzy.*')"
                                wire:navigate
                            >
                                Hasil Fuzzy
                            </flux:navlist.item>
                        </flux:navlist.group>
                    @endif

                    @if (auth()->user()->role === 'kasir')
                        <flux:navlist.group :heading="__('Menu Kasir')" class="grid">
                            <flux:navlist.item
                                icon="home"
                                :href="route('kasir.dashboard')"
                                :current="request()->routeIs('kasir.dashboard')"
                                wire:navigate
                            >
                                Dashboard
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="banknotes"
                                href="#"
                                wire:navigate
                            >
                                Pembayaran
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="wallet"
                                href="#"
                                wire:navigate
                            >
                                Kasbon / Hutang
                            </flux:navlist.item>

                            <flux:navlist.item
                                icon="document-chart-bar"
                                href="#"
                                wire:navigate
                            >
                                Laporan
                            </flux:navlist.item>
                        </flux:navlist.group>
                    @endif
                </flux:navlist>
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
