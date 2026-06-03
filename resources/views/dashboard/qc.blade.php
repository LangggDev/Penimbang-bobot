<x-layouts::app :title="'Dashboard QC'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu QC
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Dashboard QC
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Pantau daftar jenis kertas bekas yang menunggu penilaian kualitas.
                    </p>
                </div>

                <a
                    href="{{ route('qc.penilaian.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                >
                    Lihat Penilaian QC
                </a>
            </div>

            {{-- Filter Tanggal --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h3 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">Filter Tanggal</h3>
                <form method="GET" action="{{ route('qc.dashboard') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end">
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

                        <a href="{{ route('qc.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-300 dark:hover:bg-zinc-800">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Menunggu Penilaian</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['menunggu'] ?? 0, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Sudah Dinilai</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['sudah_dinilai'] ?? 0, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Revisi</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['revisi'] ?? 0, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Data Terbaru Menunggu QC
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Jenis kertas bekas yang baru masuk dari proses penimbangan pertama.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($detailTerbaru as $item)
                        <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            Belum Dinilai
                                        </span>
                                    </div>

                                    <div class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-400 sm:grid-cols-2">
                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Pelanggan:</span>
                                            {{ $item->nama_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Jenis Kertas:</span>
                                            {{ $item->nama_kertas }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Kendaraan:</span>
                                            {{ $item->nama_kendaraan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Timbang Pertama:</span>
                                            {{ number_format($item->berat_timbang_pertama, 2, ',', '.') }} kg
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('qc.penilaian.index') }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                >
                                    Lihat
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada data QC
                            </h3>

                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Data akan muncul setelah penimbang menyimpan timbangan pertama.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>