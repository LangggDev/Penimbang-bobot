<x-layouts::app :title="'Riwayat QC'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu QC
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Riwayat QC
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Lihat dan ubah kembali nilai kualitas kertas bekas yang sudah dinilai.
                    </p>
                </div>

                <a
                    href="{{ route('qc.penilaian.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                >
                    Penilaian QC
                </a>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="w-full space-y-2 md:max-w-md">
                        <label for="q" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                            Cari Riwayat QC
                        </label>

                        <input
                            id="q"
                            type="text"
                            name="q"
                            value="{{ $keyword }}"
                            placeholder="Cari kode transaksi, pelanggan, atau jenis kertas"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                        >
                            Cari
                        </button>

                        <a
                            href="{{ route('qc.riwayat.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Data Riwayat QC
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Setiap card adalah hasil penilaian QC untuk satu jenis kertas bekas.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($riwayatQc as $item)
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50 bg-white">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between bg-white dark:bg-zinc-900">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            Sudah Dinilai
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
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Nilai Kualitas:</span>
                                            <span class="tabular-nums font-semibold text-slate-900 dark:text-white">{{ number_format($item->nilai_kualitas_kertas, 2, ',', '.') }}</span>
                                        </p>

                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Timbang Pertama:</span>
                                            <span class="tabular-nums font-semibold text-slate-900 dark:text-white">{{ number_format($item->berat_timbang_pertama, 2, ',', '.') }} kg</span>
                                        </p>

                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Waktu QC:</span>
                                            <span class="tabular-nums font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($item->waktu_qc)->translatedFormat('d F Y, H:i') }}</span>
                                        </p>

                                        <p class="sm:col-span-2">
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Catatan:</span>
                                            {{ $item->catatan ?: '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <a
                                        href="{{ route('qc.riwayat.edit', $item->qc_id) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                    >
                                        Edit QC
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                Belum ada riwayat QC
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Riwayat akan muncul setelah QC melakukan penilaian kualitas kertas.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($riwayatQc->hasPages())
                    <div class="mt-6">
                        {{ $riwayatQc->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>