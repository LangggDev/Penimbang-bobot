@php
    $totalTransaksiHariIni = $totalTransaksiHariIni ?? 0;
    $totalBeratBersihHariIni = $totalBeratBersihHariIni ?? 0;
    $totalDraft = $totalDraft ?? 0;
    $totalMenungguQc = $totalMenungguQc ?? 0;
    $transaksiTerbaru = $transaksiTerbaru ?? collect();
@endphp

<x-layouts::app :title="'Dashboard Penimbang'">
    @php
        $totalTransaksiHariIni = $totalTransaksiHariIni ?? 0;
        $totalBeratBersihHariIni = $totalBeratBersihHariIni ?? 0;
        $totalDraft = $totalDraft ?? 0;
        $totalMenungguQc = $totalMenungguQc ?? 0;
        $transaksiTerbaru = $transaksiTerbaru ?? collect();
    @endphp

    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Dashboard Penimbang
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Selamat datang, {{ auth()->user()->name }}. Pantau transaksi timbang hari ini,
                        status penimbangan, dan aktivitas terbaru dari halaman ini.
                    </p>
                </div>

                <div>
                    <a href="{{ route('penimbang.transaksi.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600">
                        Buat Transaksi
                    </a>
                </div>
            </div>

            {{-- Filter Tanggal --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h3 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Filter Tanggal</h3>
                <form method="GET" action="{{ route('penimbang.dashboard') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end">
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

                        <a href="{{ route('penimbang.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- Card 1: Transaksi Hari Ini --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Transaksi Hari Ini
                            </p>
                            <h2 class="tabular-nums text-3xl font-bold text-slate-900 dark:text-white">
                                {{ number_format($totalTransaksiHariIni, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 dark:bg-teal-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Total transaksi yang dibuat hari ini.
                    </p>
                </div>

                {{-- Card 2: Berat Bersih Hari Ini --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Berat Bersih Hari Ini
                            </p>
                            <h2 class="tabular-nums text-3xl font-bold text-slate-900 dark:text-white">
                                {{ number_format($totalBeratBersihHariIni, 2, ',', '.') }}
                                <span class="text-lg font-medium text-slate-500">kg</span>
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 dark:bg-teal-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Akumulasi berat bersih dari detail barang.
                    </p>
                </div>

                {{-- Card 3: Draft Penimbangan --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Draft Penimbangan
                            </p>
                            <h2 class="tabular-nums text-3xl font-bold text-slate-900 dark:text-white">
                                {{ number_format($totalDraft, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-zinc-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Transaksi yang belum diselesaikan.
                    </p>
                </div>

                {{-- Card 4: Menunggu QC --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                                Menunggu QC
                            </p>
                            <h2 class="tabular-nums text-3xl font-bold text-slate-900 dark:text-white">
                                {{ number_format($totalMenungguQc, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Transaksi yang sudah siap dinilai QC.
                    </p>
                </div>
            </div>

            {{-- Konten utama --}}
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

                {{-- Transaksi terbaru --}}
                <div class="xl:col-span-2 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-6 space-y-1">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                            Transaksi Terbaru
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">
                            Transaksi penimbangan terbaru milik Anda.
                        </p>
                    </div>

                    <div class="space-y-4">
                        @forelse ($transaksiTerbaru as $transaksi)
                            @php
                                $statusLabel = match ($transaksi->status) {
                                    'draft_penimbangan' => 'Draft',
                                    'proses_penimbangan' => 'Proses Penimbangan',
                                    'menunggu_qc' => 'Menunggu QC',
                                    'proses_qc' => 'Proses QC',
                                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                    default => ucfirst(str_replace('_', ' ', $transaksi->status)),
                                };

                                $statusClass = match ($transaksi->status) {
                                    'draft_penimbangan'   => 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-300',
                                    'proses_penimbangan'  => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                    'menunggu_qc'         => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                    'proses_qc'           => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                    'menunggu_pembayaran' => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-300',
                                    'selesai'             => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300',
                                    'dibatalkan'          => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                    default               => 'bg-slate-100 text-slate-600 dark:bg-zinc-800 dark:text-zinc-300',
                                };
                            @endphp

                            <div class="rounded-2xl border border-slate-200 p-5 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="space-y-2">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                                {{ $transaksi->kode_transaksi }}
                                            </h3>

                                            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>

                                        <div class="space-y-0.5 text-sm text-slate-500 dark:text-zinc-400">
                                            <p>Pelanggan: {{ $transaksi->nama_pelanggan }}</p>
                                            <p>Kendaraan: {{ $transaksi->nama_kendaraan }}</p>
                                            <p>
                                                Tanggal:
                                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y, H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div>
                                        <a href="#"
                                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-14 text-center dark:border-zinc-700">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-zinc-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                    </svg>
                                </div>

                                <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">
                                    Belum ada transaksi pada periode ini.
                                </h3>
                                <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                    Transaksi penimbangan terbaru akan muncul di sini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Alur penimbang --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-6 space-y-1">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                            Alur Penimbang
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">
                            Ringkasan proses kerja petugas timbang.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-600 text-sm font-bold text-white">1</div>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">Buat Transaksi</h3>
                                <p class="text-sm text-slate-500 dark:text-zinc-400">Pilih pelanggan dan jenis kendaraan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-600 text-sm font-bold text-white">2</div>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">Tambah Barang</h3>
                                <p class="text-sm text-slate-500 dark:text-zinc-400">Masukkan jenis barang yang dibawa pelanggan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-600 text-sm font-bold text-white">3</div>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">Input Timbangan</h3>
                                <p class="text-sm text-slate-500 dark:text-zinc-400">Catat berat kotor, tara, dan berat bersih.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-teal-600 text-sm font-bold text-white">4</div>
                            <div class="space-y-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">Selesai Penimbangan</h3>
                                <p class="text-sm text-slate-500 dark:text-zinc-400">Kirim transaksi ke QC untuk penilaian.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>