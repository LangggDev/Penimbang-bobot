<x-layouts::app :title="'Timbangan Kedua'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-5xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Timbangan Kedua
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Input berat kendaraan setelah barang selesai dibongkar, lalu bagi total berat bersih ke setiap jenis kertas bekas.
                    </p>
                </div>

                <a
                    href="{{ route('penimbang.transaksi.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kode Transaksi</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->kode_transaksi }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pelanggan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->nama_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kendaraan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->nama_kendaraan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Plat Kendaraan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->plat_kendaraan ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Berat Timbangan Pertama</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ number_format($transaksi->berat_timbang_pertama, 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Status</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                        </p>
                    </div>
                </div>
            </div>

            @if ($jumlahBelumQc > 0)
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-900 dark:border-yellow-900/40 dark:bg-yellow-900/20 dark:text-yellow-200">
                    <h2 class="text-sm font-semibold">
                        QC Belum Selesai
                    </h2>

                    <p class="mt-2 text-sm leading-6">
                        Masih ada {{ $jumlahBelumQc }} jenis kertas yang belum dinilai QC.
                        Timbangan kedua baru bisa disimpan setelah semua jenis kertas selesai dinilai QC.
                    </p>
                </div>
            @endif

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="POST" action="{{ route('penimbang.transaksi.timbangan-kedua.update', $transaksi->id) }}" class="space-y-7">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="berat_timbang_kedua" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Berat Timbangan Kedua
                        </label>

                        <div class="relative">
                            <input
                                id="berat_timbang_kedua"
                                type="number"
                                step="0.01"
                                min="0"
                                name="berat_timbang_kedua"
                                value="{{ old('berat_timbang_kedua', $transaksi->berat_timbang_kedua > 0 ? $transaksi->berat_timbang_kedua : '') }}"
                                placeholder="0.00"
                                required
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 pr-12 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-zinc-500">
                                kg
                            </span>
                        </div>

                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                            Berat timbangan kedua adalah berat kendaraan setelah barang selesai dibongkar.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 text-blue-900 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-200">
                        <h2 class="text-sm font-semibold">
                            Catatan Pembagian Berat dan Harga
                        </h2>

                        <p class="mt-2 text-sm leading-6">
                            Jika pelanggan membawa lebih dari satu jenis kertas bekas, total berat bersih harus dibagi ke masing-masing jenis kertas.
                            Harga setiap jenis kertas bisa berbeda, sehingga harga tidak diisi oleh penimbang.
                            Harga per kg akan diisi nanti pada bagian kasir/pembayaran.
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Pembagian Berat Bersih per Jenis Kertas
                            </h2>

                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                Total input berat bersih semua jenis kertas harus sama dengan hasil:
                                <span class="font-semibold">berat timbang pertama - berat timbang kedua</span>.
                            </p>
                        </div>

                        <div class="space-y-3">
                            @foreach ($detailBarang as $detail)
                                <div class="rounded-2xl border border-zinc-200 p-5 dark:border-zinc-800">
                                    <div class="grid gap-4 md:grid-cols-[1fr_240px] md:items-center">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <h3 class="font-semibold text-zinc-900 dark:text-white">
                                                    {{ $detail->nama_barang }}
                                                </h3>

                                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                                    {{ $detail->status_qc === 'sudah_dinilai'
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                                    {{ ucfirst(str_replace('_', ' ', $detail->status_qc)) }}
                                                </span>
                                            </div>

                                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                                Kode: {{ $detail->kode_barang }}
                                            </p>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                Berat Bersih
                                            </label>

                                            <div class="relative">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="berat_bersih_detail[{{ $detail->detail_id }}]"
                                                    value="{{ old('berat_bersih_detail.' . $detail->detail_id, $detail->total_berat_bersih > 0 ? $detail->total_berat_bersih : '') }}"
                                                    placeholder="0.00"
                                                    required
                                                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 pr-12 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                                                >

                                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-zinc-500">
                                                    kg
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('penimbang.transaksi.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Simpan Timbangan Kedua
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>