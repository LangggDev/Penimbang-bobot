<x-layouts::app :title="'Data Pelanggan'">
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                        Data Pelanggan
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Kelola data pelanggan sebelum membuat transaksi penimbangan.
                    </p>
                </div>

                <a
                    href="{{ route('penimbang.pelanggan.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                >
                    Tambah Pelanggan
                </a>
            </div>

            {{-- Session Alert --}}
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Total Pelanggan</p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($summary['total'], 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 dark:bg-teal-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Pelanggan Aktif</p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($summary['aktif'], 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50 dark:bg-green-900/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-3">
                            <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Pelanggan Nonaktif</p>
                            <h2 class="tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                                {{ number_format($summary['nonaktif'], 0, ',', '.') }}
                            </h2>
                        </div>
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-zinc-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="grid gap-4 md:grid-cols-2 lg:grid-cols-[1fr_220px_auto] lg:items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                            Cari Pelanggan
                        </label>

                        <input
                            type="text"
                            name="q"
                            value="{{ $keyword }}"
                            placeholder="Cari kode, nama, nomor HP, atau alamat"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                            <option value="semua" @selected($status === 'semua')>Semua</option>
                            <option value="aktif" @selected($status === 'aktif')>Aktif</option>
                            <option value="nonaktif" @selected($status === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>

                    <div class="flex gap-3 md:col-span-2 lg:col-span-1 lg:self-end">
                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                        >
                            Cari
                        </button>

                        <a
                            href="{{ route('penimbang.pelanggan.index') }}"
                            class="flex-1 rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 text-center transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Daftar Pelanggan --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Daftar Pelanggan
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Pilih pelanggan untuk memulai transaksi penimbangan.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($pelanggan as $item)
                        <div class="rounded-2xl border border-slate-200 p-5 transition hover:bg-slate-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ $item->nama_pelanggan }}
                                        </h3>

                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            {{ $item->status === 'aktif'
                                                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>

                                    <div class="grid gap-2 text-sm text-slate-600 dark:text-zinc-400 sm:grid-cols-2">
                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Kode:</span>
                                            {{ $item->kode_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Nomor HP:</span>
                                            {{ $item->no_hp ?: '-' }}
                                        </p>

                                        <p class="sm:col-span-2">
                                            <span class="font-medium text-slate-800 dark:text-zinc-200">Alamat:</span>
                                            {{ $item->alamat ?: '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row lg:flex-col">
                                    <a
                                        href="{{ route('penimbang.pelanggan.timbangan-pertama', $item->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700"
                                    >
                                        Pilih Pelanggan
                                    </a>

                                    <a
                                        href="{{ route('penimbang.pelanggan.edit', $item->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('penimbang.pelanggan.destroy', $item->id) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus/nonaktifkan pelanggan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center rounded-xl border border-red-300 px-4 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-900/20"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-14 text-center dark:border-zinc-700">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-slate-400 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4 0-7 2-7 4v1h14v-1c0-2-3-4-7-4z" />
                                </svg>
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">
                                Belum ada pelanggan
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Tambahkan pelanggan terlebih dahulu sebelum membuat transaksi.
                            </p>

                            <a href="{{ route('penimbang.pelanggan.create') }}" class="mt-4 inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-700">
                                Tambah Pelanggan
                            </a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $pelanggan->links() }}
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>