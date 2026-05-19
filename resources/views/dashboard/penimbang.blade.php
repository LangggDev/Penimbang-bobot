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
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Sistem Penilaian Bobot Ketidaklayakan
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Dashboard Penimbang
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Selamat datang, {{ auth()->user()->name }}. Pantau transaksi timbang hari ini,
                        status penimbangan, dan aktivitas terbaru dari halaman ini.
                    </p>
                </div>

                <div>
                    <a href="{{ route('penimbang.transaksi.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                        Buat Transaksi
                    </a>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- Card 1 --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Transaksi Hari Ini
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($totalTransaksiHariIni, 0, ',', '.') }}
                            </h2>
                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Total transaksi yang dibuat hari ini.
                    </p>
                </div>

                {{-- Card 2 --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Berat Bersih Hari Ini
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($totalBeratBersihHariIni, 2, ',', '.') }}
                                <span class="text-lg font-medium text-zinc-500">kg</span>
                            </h2>
                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Akumulasi berat bersih dari detail barang.
                    </p>
                </div>

                {{-- Card 3 --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Draft Penimbangan
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($totalDraft, 0, ',', '.') }}
                            </h2>
                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Transaksi yang belum diselesaikan.
                    </p>
                </div>

                {{-- Card 4 --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                Menunggu QC
                            </p>
                            <h2 class="text-3xl font-bold text-zinc-900 dark:text-white">
                                {{ number_format($totalMenungguQc, 0, ',', '.') }}
                            </h2>
                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Transaksi yang sudah siap dinilai QC.
                    </p>
                </div>
            </div>

            {{-- Konten utama --}}
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

                {{-- Transaksi terbaru --}}
                <div class="xl:col-span-2 rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-6 space-y-1">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                            Transaksi Terbaru
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Ditampilkan dalam bentuk card agar lebih ringkas dan mudah dibaca.
                        </p>
                    </div>

                    <div class="space-y-4">
                        @forelse ($transaksiTerbaru as $transaksi)
                            @php
                                $statusLabel = match ($transaksi->status) {
                                    'draft_penimbangan' => 'Draft',
                                    'menunggu_qc' => 'Menunggu QC',
                                    'proses_qc' => 'Proses QC',
                                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                    default => ucfirst(str_replace('_', ' ', $transaksi->status)),
                                };

                                $statusClass = match ($transaksi->status) {
                                    'draft_penimbangan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                    'menunggu_qc' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    'proses_qc' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                    'menunggu_pembayaran' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                    'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                    'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                    default => 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200',
                                };
                            @endphp

                            <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="space-y-2">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-base font-semibold text-zinc-900 dark:text-white">
                                                {{ $transaksi->kode_transaksi }}
                                            </h3>

                                            <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>

                                        <div class="space-y-1 text-sm text-zinc-500 dark:text-zinc-400">
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
                                            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-zinc-500 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2h-3.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0011.586 3H6a2 2 0 00-2 2v8m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4m-8 0H4m8 0v-2" />
                                    </svg>
                                </div>

                                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">
                                    Belum ada transaksi
                                </h3>
                                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                    Transaksi penimbangan terbaru akan muncul di sini.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Alur penimbang --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-6 space-y-1">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                            Alur Penimbang
                        </h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Ringkasan proses kerja petugas timbang.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-sm font-semibold text-white dark:bg-white dark:text-zinc-900">1</div>
                            <div class="space-y-1">
                                <h3 class="font-medium text-zinc-900 dark:text-white">Buat Transaksi</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Pilih pelanggan dan jenis kendaraan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-sm font-semibold text-white dark:bg-white dark:text-zinc-900">2</div>
                            <div class="space-y-1">
                                <h3 class="font-medium text-zinc-900 dark:text-white">Tambah Barang</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Masukkan jenis barang yang dibawa pelanggan.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-sm font-semibold text-white dark:bg-white dark:text-zinc-900">3</div>
                            <div class="space-y-1">
                                <h3 class="font-medium text-zinc-900 dark:text-white">Input Timbangan</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Catat berat kotor, tara, dan berat bersih.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-900 text-sm font-semibold text-white dark:bg-white dark:text-zinc-900">4</div>
                            <div class="space-y-1">
                                <h3 class="font-medium text-zinc-900 dark:text-white">Selesaikan</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">Kirim transaksi ke QC untuk penilaian.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>