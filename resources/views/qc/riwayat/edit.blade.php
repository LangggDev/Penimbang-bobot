<x-layouts::app :title="'Edit Riwayat QC'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-4xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu QC
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Edit Riwayat QC
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Perbarui nilai kualitas kertas jika terjadi kesalahan input penilaian.
                    </p>
                </div>

                <a
                    href="{{ route('qc.riwayat.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kode Transaksi</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $qc->kode_transaksi }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pelanggan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $qc->nama_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Jenis Kertas Bekas</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $qc->nama_kertas }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kendaraan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $qc->nama_kendaraan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Timbang Pertama</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ number_format($qc->berat_timbang_pertama, 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Waktu QC Terakhir</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($qc->waktu_qc)->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="POST" action="{{ route('qc.riwayat.update', $qc->qc_id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="nilai_kualitas_kertas" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Nilai Kualitas Kertas
                        </label>

                        <input
                            id="nilai_kualitas_kertas"
                            type="number"
                            name="nilai_kualitas_kertas"
                            min="1"
                            max="10"
                            step="0.01"
                            value="{{ old('nilai_kualitas_kertas', $qc->nilai_kualitas_kertas) }}"
                            required
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >

                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Skala nilai: 1-3 buruk, 4-7 sedang, 8-10 baik.
                        </p>

                        @error('nilai_kualitas_kertas')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="catatan" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Catatan QC
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="4"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >{{ old('catatan', $qc->catatan) }}</textarea>

                        @error('catatan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-900 dark:border-yellow-900/40 dark:bg-yellow-900/20 dark:text-yellow-200">
                        <h2 class="text-sm font-semibold">
                            Catatan
                        </h2>

                        <p class="mt-2 text-sm leading-6">
                            Jika nanti hasil fuzzy sudah dibuat, perubahan nilai QC harus menghitung ulang hasil fuzzy.
                            Untuk tahap saat ini, perubahan hanya memperbarui nilai kualitas kertas.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('qc.riwayat.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>