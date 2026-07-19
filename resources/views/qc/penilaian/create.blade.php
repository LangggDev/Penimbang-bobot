<x-layouts::app :title="'Input Penilaian QC'">
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-4xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu QC
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                        Input Penilaian QC
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Berikan nilai kualitas kertas bekas berdasarkan kondisi fisik saat proses bongkar barang.
                    </p>
                </div>

                <a
                    href="{{ route('qc.penilaian.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-t-4 border-t-teal-600">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Kode Transaksi</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $detail->kode_transaksi }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Pelanggan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $detail->nama_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Jenis Kertas Bekas</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $detail->nama_kertas }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Kendaraan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $detail->nama_kendaraan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Berat Sebelum Bongkar</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format($detail->total_berat_kotor, 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Status QC</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ ucfirst(str_replace('_', ' ', $detail->status_qc)) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="POST" action="{{ route('qc.penilaian.store', $detail->detail_id) }}" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label for="nilai_kualitas_kertas" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                            Nilai Kualitas Kertas
                        </label>

                        <input
                            id="nilai_kualitas_kertas"
                            type="number"
                            name="nilai_kualitas_kertas"
                            min="1"
                            max="10"
                            step="0.01"
                            value="{{ old('nilai_kualitas_kertas') }}"
                            required
                            placeholder="Masukkan nilai 1 sampai 10"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >

                        <p class="text-xs text-teal-600 dark:text-teal-400 font-medium bg-teal-50 dark:bg-teal-950/20 px-3 py-1.5 rounded-lg inline-block">
                            Skala nilai: 1-3 buruk, 4-7 sedang, 8-10 baik.
                        </p>

                        @error('nilai_kualitas_kertas')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="catatan" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                            Catatan QC
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="4"
                            placeholder="Contoh: kertas basah, tercampur plastik, kondisi baik, dan sebagainya"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-teal-100 bg-teal-50 p-5 dark:border-teal-900/30 dark:bg-teal-900/10">
                        <h2 class="text-sm font-semibold text-teal-800 dark:text-teal-300">
                            Informasi
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-teal-700 dark:text-teal-400">
                            Nilai QC ini disimpan per jenis kertas. Perhitungan fuzzy final akan dilakukan setelah
                            timbangan kedua dan berat bersih per jenis kertas sudah tersedia.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('qc.penilaian.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                        >
                            Simpan Penilaian QC
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>