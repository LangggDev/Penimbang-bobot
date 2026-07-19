<x-layouts::app :title="'Dashboard QC'">
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu Quality Control
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                        Dashboard QC
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Pantau daftar jenis kertas bekas yang menunggu penilaian kualitas.
                    </p>
                </div>

                <a
                    href="{{ route('qc.penilaian.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 dark:bg-teal-500 dark:hover:bg-teal-600"
                >
                    Lihat Penilaian QC
                </a>
            </div>

            {{-- Filter Tanggal --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h3 class="mb-4 text-sm font-semibold text-slate-900 dark:text-white">Filter Tanggal</h3>
                <form method="GET" action="{{ route('qc.dashboard') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end">
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

                        <a href="{{ route('qc.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                {{-- Menunggu Penilaian --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Menunggu Penilaian</p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($summary['menunggu'] ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 dark:bg-teal-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Barang yang belum dinilai QC.
                    </p>
                </div>

                {{-- Sudah Dinilai --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Sudah Dinilai</p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($summary['sudah_dinilai'] ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Barang yang sudah selesai dinilai QC.
                    </p>
                </div>

                {{-- Revisi --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Revisi</p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($summary['revisi'] ?? 0, 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-zinc-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-zinc-400">
                        Penilaian yang direvisi atau diperbarui.
                    </p>
                </div>
            </div>

            {{-- Data Terbaru Menunggu QC --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Data Terbaru Menunggu QC
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Jenis kertas bekas yang baru masuk dari proses penimbangan pertama.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($detailTerbaru as $item)
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                            Belum Dinilai
                                        </span>
                                    </div>

                                    <div class="grid gap-2 text-sm text-slate-600 dark:text-zinc-400 sm:grid-cols-2">
                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Pelanggan:</span>
                                            {{ $item->nama_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Jenis Kertas:</span>
                                            {{ $item->nama_kertas }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Kendaraan:</span>
                                            {{ $item->nama_kendaraan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Timbang Pertama:</span>
                                            <span class="tabular-nums">{{ number_format($item->berat_timbang_pertama, 2, ',', '.') }}</span> kg
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('qc.penilaian.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                >
                                    Nilai
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-14 text-center dark:border-zinc-700">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">
                                Belum ada barang yang menunggu QC.
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Data akan muncul setelah penimbang menyimpan timbangan pertama.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>