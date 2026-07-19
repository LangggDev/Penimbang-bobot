<x-layouts::app :title="'Penilaian QC'">
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="space-y-2">
                <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                    Menu QC
                </p>

                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                    Penilaian QC
                </h1>

                <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                    Nilai kualitas kertas bekas per jenis kertas. Setiap jenis kertas dalam satu transaksi dinilai secara terpisah.
                </p>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-l-4 border-l-teal-500">
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                        Menunggu Penilaian
                    </p>

                    <h2 class="mt-3 tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                        {{ number_format($summary['menunggu'] ?? 0, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-l-4 border-l-green-600">
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                        Sudah Dinilai
                    </p>

                    <h2 class="mt-3 tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                        {{ number_format($summary['sudah_dinilai'] ?? 0, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-l-4 border-l-red-500">
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">
                        Revisi
                    </p>

                    <h2 class="mt-3 tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                        {{ number_format($summary['revisi'] ?? 0, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            {{-- List Menunggu QC --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Daftar Jenis Kertas Menunggu QC
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Setiap card adalah satu jenis kertas bekas dalam transaksi pelanggan.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($detailMenungguQc as $item)
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50 bg-white">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between bg-white dark:bg-zinc-900">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
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
                                            <span class="tabular-nums font-semibold">{{ number_format($item->berat_timbang_pertama, 2, ',', '.') }} kg</span>
                                        </p>
                                    </div>

                                    <p class="text-sm text-slate-500 dark:text-zinc-400">
                                        Tanggal:
                                        {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>

                                <div>
                                    <a
                                        href="{{ route('qc.penilaian.create', $item->detail_id) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                                    >
                                        Nilai QC
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                Tidak ada data menunggu QC
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Data akan muncul setelah penimbang membuat transaksi dan memilih jenis kertas bekas.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($detailMenungguQc->hasPages())
                    <div class="mt-6">
                        {{ $detailMenungguQc->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>