<x-layouts::app :title="'Dashboard Kasir'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Sistem Penilaian Bobot Ketidaklayakan
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Dashboard Kasir
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Selamat datang, {{ auth()->user()->name }}. Pantau pembayaran, kasbon, dan transaksi hari ini dari halaman ini.
                    </p>
                </div>

                <div>
                    <a href="{{ route('kasir.pembayaran.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                        Lihat Pembayaran
                    </a>
                </div>
            </div>

            {{-- Filter Tanggal --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h3 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">Filter Tanggal</h3>
                <form method="GET" action="{{ route('kasir.dashboard') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end">
                    <div class="flex-1">
                        <label for="tanggal_mulai" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai ?? now()->toDateString() }}" class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:focus:border-white dark:focus:ring-white">
                    </div>

                    <div class="flex-1">
                        <label for="tanggal_selesai" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai ?? now()->toDateString() }}" class="w-full rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm text-zinc-900 placeholder-zinc-400 focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white dark:focus:border-white dark:focus:ring-white">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>

                        <a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- Card 1: Total Pembayaran --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Total Pembayaran
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($totalPembayaran ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Jumlah transaksi pembayaran dalam periode ini.
                    </p>
                </div>

                {{-- Card 2: Total Dibayar Ke Pelanggan --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Total Dibayar Ke Pelanggan
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                Rp {{ number_format($totalDibayarKePelanggan ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Total nilai yang dibayarkan kepada pelanggan.
                    </p>
                </div>

                {{-- Card 3: Total Kasbon --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Total Kasbon
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($totalKasbon ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Jumlah hutang kasbon pelanggan dalam periode ini.
                    </p>
                </div>

                {{-- Card 4: Total Potongan Kasbon --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Total Potongan Kasbon
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                Rp {{ number_format($totalPotonganKasbon ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Total potongan dari kasbon dalam pembayaran.
                    </p>
                </div>
            </div>

            {{-- Konten Utama --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6 space-y-1">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Pembayaran Terbaru
                    </h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Transaksi pembayaran terbaru dalam periode yang dipilih.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($pembayaranTerbaru ?? collect() as $pembayaran)
                        <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-2">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-base font-semibold text-zinc-900 dark:text-white">
                                            {{ $pembayaran->kode_pembayaran }}
                                        </h3>
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            Selesai
                                        </span>
                                    </div>

                                    <div class="space-y-1 text-sm text-zinc-500 dark:text-zinc-400">
                                        <p>Transaksi: {{ $pembayaran->kode_transaksi }}</p>
                                        <p>Pelanggan: {{ $pembayaran->nama_pelanggan }}</p>
                                        <p>
                                            Tanggal:
                                            {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y') }}
                                        </p>
                                        <p class="font-medium text-zinc-700 dark:text-zinc-300">
                                            Pembayaran: Rp {{ number_format($pembayaran->total_transaksi, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <a href="{{ route('kasir.pembayaran.index') }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada pembayaran
                            </h3>

                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Pembayaran akan muncul di sini setelah transaksi diproses.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>