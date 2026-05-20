<x-layouts::app :title="'Hasil Fuzzy'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu QC
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Hasil Fuzzy Tsukamoto
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Menampilkan hasil bobot ketidaklayakan, potongan berat, dan berat layak dari perhitungan fuzzy.
                    </p>
                </div>

                <a
                    href="{{ route('qc.penilaian.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali ke QC
                </a>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="w-full space-y-2 md:max-w-md">
                        <label for="q" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Cari Hasil Fuzzy
                        </label>

                        <input
                            id="q"
                            type="text"
                            name="q"
                            value="{{ $keyword }}"
                            placeholder="Cari kode transaksi, pelanggan, atau jenis kertas"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Cari
                        </button>

                        <a
                            href="{{ route('qc.fuzzy.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Daftar Hasil Fuzzy
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Setiap card mewakili satu jenis kertas bekas yang sudah dihitung fuzzy.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($hasilFuzzy as $item)
                        <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            Fuzzy Selesai
                                        </span>
                                    </div>

                                    <div class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-400 sm:grid-cols-2 lg:grid-cols-3">
                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Pelanggan:</span>
                                            {{ $item->nama_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Jenis Kertas:</span>
                                            {{ $item->nama_barang }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Kualitas QC:</span>
                                            {{ number_format($item->nilai_kualitas_kertas, 2, ',', '.') }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Berat Bersih:</span>
                                            {{ number_format($item->total_berat_bersih, 2, ',', '.') }} kg
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Bobot Tidak Layak:</span>
                                            {{ number_format($item->nilai_bobot_ketidaklayakan, 2, ',', '.') }}%
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Berat Layak:</span>
                                            {{ number_format($item->berat_layak, 2, ',', '.') }} kg
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('qc.fuzzy.show', $item->fuzzy_id) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                                >
                                    Detail Perhitungan
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada hasil fuzzy
                            </h3>

                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Hasil akan muncul otomatis setelah berat bersih dan nilai QC sudah tersedia.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($hasilFuzzy->hasPages())
                    <div class="mt-6">
                        {{ $hasilFuzzy->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>