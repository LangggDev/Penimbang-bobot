<x-layouts::app :title="'Timbangan Pertama'">
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-5xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                        Timbangan Pertama
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Buat transaksi awal untuk pelanggan yang baru datang. Pilih jenis kertas bekas yang dibawa,
                        lalu simpan berat kendaraan pada timbangan pertama.
                    </p>
                </div>

                <a
                    href="{{ route('penimbang.pelanggan.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Kode Pelanggan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $pelanggan->kode_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Nama Pelanggan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $pelanggan->nama_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Nomor HP</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $pelanggan->no_hp ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="POST" action="{{ route('penimbang.pelanggan.timbangan-pertama.store', $pelanggan->id) }}" class="space-y-7">
                    @csrf

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="jenis_kendaraan_id" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                Jenis Kendaraan
                            </label>

                            <select
                                id="jenis_kendaraan_id"
                                name="jenis_kendaraan_id"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
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

                        <div class="space-y-2">
                            <label for="plat_kendaraan" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                Plat Kendaraan
                            </label>

                            <input
                                id="plat_kendaraan"
                                type="text"
                                name="plat_kendaraan"
                                value="{{ old('plat_kendaraan') }}"
                                placeholder="Contoh: B 1234 ABC"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >

                            @error('plat_kendaraan')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="tanggal_transaksi" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                Waktu Timbangan Pertama
                            </label>

                            <input
                                id="tanggal_transaksi"
                                type="datetime-local"
                                name="tanggal_transaksi"
                                value="{{ old('tanggal_transaksi', now()->format('Y-m-d\TH:i')) }}"
                                required
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >

                            @error('tanggal_transaksi')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="berat_timbang_pertama" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                Berat Timbangan Pertama
                            </label>

                            <div class="relative">
                                <input
                                    id="berat_timbang_pertama"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="berat_timbang_pertama"
                                    value="{{ old('berat_timbang_pertama') }}"
                                    placeholder="0.00"
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                                >

                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">
                                    kg
                                </span>
                            </div>

                            @error('berat_timbang_pertama')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                Jenis Kertas Bekas yang Dibawa
                            </label>

                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Pilih satu atau lebih jenis kertas. Jika pelanggan membawa beberapa jenis, semuanya tetap masuk dalam satu transaksi.
                            </p>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($jenisKertasBekas as $item)
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 p-4 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                                    <input
                                        type="checkbox"
                                        name="jenis_kertas_bekas_ids[]"
                                        value="{{ $item->id }}"
                                        @checked(in_array($item->id, old('jenis_kertas_bekas_ids', [])))
                                        class="mt-1 rounded border-slate-300 text-teal-600 focus:ring-teal-500 focus:ring-offset-2"
                                    >

                                    <span>
                                        <span class="block font-semibold text-slate-900 dark:text-white">
                                            {{ $item->nama_barang }}
                                        </span>
                                        <span class="mt-1 block text-sm text-slate-500 dark:text-slate-400">
                                            {{ $item->kode_barang }}
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @error('jenis_kertas_bekas_ids')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @error('jenis_kertas_bekas_ids.*')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="catatan" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                            Catatan
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="4"
                            placeholder="Catatan tambahan jika ada"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >{{ old('catatan') }}</textarea>

                        @error('catatan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-teal-100 bg-teal-50 p-5 dark:border-teal-900/30 dark:bg-teal-900/10">
                        <h2 class="text-sm font-semibold text-teal-800 dark:text-teal-300">
                            Alur Setelah Disimpan
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-teal-700 dark:text-teal-400">
                            Sistem akan membuat transaksi dengan status Draft Penimbangan. Jenis kertas yang dipilih
                            akan masuk sebagai detail barang. Setelah bongkar selesai, petugas akan mengisi timbangan kedua
                            dan membagi berat bersih per jenis barang.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('penimbang.pelanggan.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                        >
                            Simpan Timbangan Pertama
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts::app>