<x-layouts::app :title="'Edit Pelanggan'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-4xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Edit Pelanggan
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Perbarui data pelanggan. Kode pelanggan tidak diubah agar riwayat tetap konsisten.
                    </p>
                </div>

                <a
                    href="{{ route('penimbang.pelanggan.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="POST" action="{{ route('penimbang.pelanggan.update', $pelanggan->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Kode Pelanggan
                            </label>

                            <input
                                type="text"
                                value="{{ $pelanggan->kode_pelanggan }}"
                                readonly
                                class="w-full rounded-xl border border-zinc-300 bg-zinc-100 px-4 py-3 text-sm text-zinc-900 shadow-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                            >
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                required
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >
                                <option value="aktif" @selected(old('status', $pelanggan->status) === 'aktif')>Aktif</option>
                                <option value="nonaktif" @selected(old('status', $pelanggan->status) === 'nonaktif')>Nonaktif</option>
                            </select>

                            @error('status')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="nama_pelanggan" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Nama Pelanggan
                        </label>

                        <input
                            id="nama_pelanggan"
                            type="text"
                            name="nama_pelanggan"
                            value="{{ old('nama_pelanggan', $pelanggan->nama_pelanggan) }}"
                            required
                            autofocus
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >

                        @error('nama_pelanggan')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="no_hp" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Nomor HP
                        </label>

                        <input
                            id="no_hp"
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $pelanggan->no_hp) }}"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >

                        @error('no_hp')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="alamat" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Alamat
                        </label>

                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="4"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >{{ old('alamat', $pelanggan->alamat) }}</textarea>

                        @error('alamat')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-800 dark:bg-zinc-950">
                        <h2 class="text-sm font-semibold text-zinc-900 dark:text-white">
                            Catatan Status
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                            Status nonaktif digunakan untuk menyembunyikan pelanggan dari pilihan transaksi baru,
                            bukan untuk menandai hutang. Status hutang tetap dicatat di tabel hutang pelanggan.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a
                            href="{{ route('penimbang.pelanggan.index') }}"
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