<x-layouts::app :title="'Kasbon / Hutang Pelanggan'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Kasir
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Kasbon / Hutang Pelanggan
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Kelola kasbon pelanggan. Kasbon akan dipakai sebagai potongan uang saat pembayaran transaksi.
                    </p>
                </div>

                <a
                    href="{{ route('kasir.kasbon.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                >
                    Tambah Kasbon
                </a>
            </div>

            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Kasbon Aktif</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['belum_lunas'], 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Kasbon Lunas</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['lunas'], 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Sisa Kasbon</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        Rp{{ number_format($summary['total_sisa_hutang'], 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 text-blue-900 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-200">
                <h2 class="text-sm font-semibold">Catatan Kasbon</h2>
                <p class="mt-2 text-sm leading-6">
                    Kasbon tidak mengurangi berat barang dan tidak mengubah hasil fuzzy. Kasbon hanya mengurangi uang yang diterima pelanggan saat pembayaran.
                </p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="grid gap-4 md:grid-cols-[1fr_220px_auto_auto] md:items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Cari
                        </label>
                        <input
                            type="text"
                            name="q"
                            value="{{ $keyword }}"
                            placeholder="Cari kode, pelanggan, atau kode pelanggan"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Status
                        </label>
                        <select
                            name="status"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                            <option value="semua" @selected($status === 'semua')>Semua Status</option>
                            <option value="belum_lunas" @selected($status === 'belum_lunas')>Belum Lunas</option>
                            <option value="lunas" @selected($status === 'lunas')>Lunas</option>
                        </select>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Terapkan
                    </button>

                    <a
                        href="{{ route('kasir.kasbon.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                    >
                        Reset
                    </a>
                </form>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Daftar Kasbon
                    </h2>
                </div>

                <div class="space-y-4">
                    @forelse ($hutang as $item)
                        <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                            {{ $item->kode_hutang }}
                                        </h3>

                                        @if ($item->status === 'belum_lunas')
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                Belum Lunas
                                            </span>
                                        @else
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                Lunas
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-400 sm:grid-cols-2 lg:grid-cols-4">
                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Pelanggan:</span>
                                            {{ $item->nama_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Total Kasbon:</span>
                                            Rp{{ number_format($item->total_hutang, 0, ',', '.') }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Terbayar:</span>
                                            Rp{{ number_format($item->total_terbayar, 0, ',', '.') }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Sisa:</span>
                                            Rp{{ number_format($item->sisa_hutang, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                                    <a
                                        href="{{ route('kasir.kasbon.edit', $item->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('kasir.kasbon.destroy', $item->id) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus kasbon ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex w-full items-center justify-center rounded-xl border border-red-300 px-5 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-900/50 dark:text-red-300 dark:hover:bg-red-900/20"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada data kasbon
                            </h3>
                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Tambahkan kasbon pelanggan terlebih dahulu.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($hutang->hasPages())
                    <div class="mt-6">
                        {{ $hutang->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>