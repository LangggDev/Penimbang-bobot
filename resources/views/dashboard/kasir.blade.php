<x-layouts::app :title="'Dashboard Kasir'">
@php $enableKasbon = false; @endphp
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu Kasir
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                        Dashboard Kasir
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Selamat datang, {{ auth()->user()->name }}. Pantau pembayaran, kasbon, dan transaksi hari ini dari halaman ini.
                    </p>
                </div>

                <div>
                    <a href="{{ route('kasir.pembayaran.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600">
                        Lihat Pembayaran
                    </a>
                </div>
            </div>

            {{-- Filter Tanggal --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h3 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Filter Tanggal</h3>
                <form method="GET" action="{{ route('kasir.dashboard') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-2">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai ?? now()->toDateString() }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                    </div>

                    <div class="flex-1">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-slate-700 dark:text-zinc-300 mb-2">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai ?? now()->toDateString() }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 {{ $enableKasbon ? 'xl:grid-cols-4' : 'xl:grid-cols-2' }}">

                {{-- Card 1: Total Pembayaran --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Total Pembayaran
                            </p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 dark:bg-teal-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Jumlah transaksi pembayaran dalam periode ini.
                    </p>
                </div>

                {{-- Card 2: Total Dibayar Ke Pelanggan --}}
                <div class="rounded-2xl border border-teal-200 bg-teal-50 p-6 shadow-sm dark:border-teal-900/40 dark:bg-teal-900/10">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-teal-700 dark:text-teal-400">
                                Total Dibayar Ke Pelanggan
                            </p>
                            <h2 class="tabular-nums text-2xl font-bold text-teal-800 sm:text-3xl dark:text-teal-300">
                                Rp {{ number_format($totalDibayarKePelanggan ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-100 dark:bg-teal-900/50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-700 dark:text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-teal-600 dark:text-teal-400">
                        Total nilai yang dibayarkan kepada pelanggan.
                    </p>
                </div>

                {{-- Card 3: Total Kasbon (sembunyikan via feature flag) --}}
                @if ($enableKasbon)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Total Kasbon
                            </p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($totalKasbon ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Jumlah hutang kasbon pelanggan dalam periode ini.
                    </p>
                </div>

                {{-- Card 4: Total Potongan Kasbon (sembunyikan via feature flag) --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Total Potongan Kasbon
                            </p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                Rp {{ number_format($totalPotonganKasbon ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-zinc-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Total potongan dari kasbon dalam pembayaran.
                    </p>
                </div>
                @endif
            </div>

            {{-- Pembayaran Terbaru --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6 space-y-1">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Pembayaran Terbaru
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-zinc-400">
                        Transaksi pembayaran terbaru dalam periode yang dipilih.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($pembayaranTerbaru ?? collect() as $pembayaran)
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $pembayaran->kode_pembayaran }}
                                        </h3>
                                        <span class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                            Selesai
                                        </span>
                                    </div>

                                    <div class="space-y-0.5 text-sm text-slate-500 dark:text-zinc-400">
                                        <p>Transaksi: {{ $pembayaran->kode_transaksi }}</p>
                                        <p>Pelanggan: {{ $pembayaran->nama_pelanggan }}</p>
                                        <p>
                                            Tanggal:
                                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y') }}
                                        </p>
                                        <p class="font-semibold text-slate-700 dark:text-zinc-300">
                                            Pembayaran: <span class="tabular-nums">Rp {{ number_format($pembayaran->total_transaksi, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <a href="{{ route('kasir.pembayaran.index') }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-14 text-center dark:border-zinc-700">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">
                                Belum ada data pembayaran.
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Pembayaran akan muncul di sini setelah transaksi diproses.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>