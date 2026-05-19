<x-layouts::app :title="'Buat Transaksi Penimbangan'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-4xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Buat Transaksi Penimbangan
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Buat transaksi awal berdasarkan pelanggan dan kendaraan. Setelah transaksi dibuat,
                        barang dan riwayat timbang akan ditambahkan pada tahap berikutnya.
                    </p>
                </div>

                <a
                    href="{{ route('penimbang.transaksi.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="POST" action="{{ route('penimbang.transaksi.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="pelanggan_id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Pelanggan
                            </label>

                            <select
                                id="pelanggan_id"
                                name="pelanggan_id"
                                required
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >
                                <option value="">Pilih pelanggan</option>
                                @foreach ($pelanggan as $item)
                                    <option value="{{ $item->id }}" @selected(old('pelanggan_id') == $item->id)>
                                        {{ $item->kode_pelanggan }} - {{ $item->nama_pelanggan }}
                                    </option>
                                @endforeach
                            </select>

                            @error('pelanggan_id')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="jenis_kendaraan_id" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Jenis Kendaraan
                            </label>

                            <select
                                id="jenis_kendaraan_id"
                                name="jenis_kendaraan_id"
                                required
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >
                                <option value="">Pilih kendaraan</option>
                                @foreach ($jenisKendaraan as $item)
                                    <option value="{{ $item->id }}" @selected(old('jenis_kendaraan_id') == $item->id)>
                                        {{ $item->nama_kendaraan }} - {{ $item->kategori_kendaraan }}
                                    </option>
                                @endforeach
                            </select>

                            @error('jenis_kendaraan_id')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="tanggal_transaksi" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Tanggal Transaksi
                        </label>

                        <input
                            id="tanggal_transaksi"
                            type="datetime-local"
                            name="tanggal_transaksi"
                            value="{{ old('tanggal_transaksi', now()->format('Y-m-d\TH:i')) }}"
                            required
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >

                        @error('tanggal_transaksi')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="catatan" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Catatan
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="4"
                            placeholder="Catatan tambahan jika ada"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-800 dark:bg-zinc-950">
                        <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">
                            Informasi
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                            Setelah disimpan, status transaksi otomatis menjadi
                            <span class="font-semibold">Draft Penimbangan</span>.
                            Tahap berikutnya adalah menambahkan barang dan riwayat timbang.
                        </p>
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
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>