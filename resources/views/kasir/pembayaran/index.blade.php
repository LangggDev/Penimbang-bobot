<x-layouts::app :title="'Pembayaran Kasir'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Kasir
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Transaksi Menunggu Pembayaran
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Daftar transaksi yang sudah selesai penimbangan, sudah dinilai QC, dan siap diproses pembayaran.
                    </p>
                </div>
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
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menunggu Pembayaran
                    </p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['menunggu_pembayaran'], 0, ',', '.') }}
                    </h2>
                    <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                        Total transaksi dengan status menunggu pembayaran.
                    </p>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Total Berat Layak Pending
                    </p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['total_berat_layak_pending'], 2, ',', '.') }} kg
                    </h2>
                    <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                        Akumulasi berat layak dari transaksi yang belum dibayar.
                    </p>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Pembayaran Tersimpan
                    </p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['sudah_dibayar'], 0, ',', '.') }}
                    </h2>
                    <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                        Total pembayaran yang sudah diproses kasir.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 text-blue-900 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-200">
                <h2 class="text-sm font-semibold">
                    Rumus Pembayaran
                </h2>

                <p class="mt-2 text-sm leading-6">
                    Pembayaran mengikuti alur lapangan:
                    <span class="font-semibold">Berat Bersih - Bobot Ketidaklayakan (%) = Berat Layak</span>,
                    lalu <span class="font-semibold">Berat Layak × Harga per Kg = Total Bayar</span>.
                    Sistem tetap menghitung potongan berat dari persentase agar hasilnya akurat.
                </p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="w-full space-y-2 md:max-w-md">
                        <label for="q" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Cari Transaksi
                        </label>

                        <input
                            id="q"
                            type="text"
                            name="q"
                            value="{{ $keyword }}"
                            placeholder="Cari kode transaksi, pelanggan, atau plat kendaraan"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Cari
                        </button>

                        <a
                            href="{{ route('kasir.pembayaran.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Daftar Transaksi
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Pilih transaksi untuk mulai input harga per kg dan menyimpan pembayaran.
                    </p>
                </div>

                <div class="space-y-4">
                    @forelse ($transaksi as $item)
                        @php
                            $jumlahSiapBayar = $item->jumlah_siap_bayar ?? 0;
                            $siapBayar = $item->jumlah_barang > 0 && $item->jumlah_barang == $jumlahSiapBayar;
                        @endphp

                        <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                            Menunggu Pembayaran
                                        </span>

                                        @if ($siapBayar)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                Fuzzy Lengkap
                                            </span>
                                        @else
                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                Fuzzy Belum Lengkap
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-400 sm:grid-cols-2 lg:grid-cols-3">
                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Pelanggan:</span>
                                            {{ $item->nama_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Kendaraan:</span>
                                            {{ $item->nama_kendaraan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Plat:</span>
                                            {{ $item->plat_kendaraan ?: '-' }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Jumlah Barang:</span>
                                            {{ $item->jumlah_barang }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Berat Bersih:</span>
                                            {{ number_format($item->total_berat_bersih, 2, ',', '.') }} kg
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Berat Layak:</span>
                                            {{ number_format($item->total_berat_layak, 2, ',', '.') }} kg
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                                    <a
                                        href="{{ route('kasir.pembayaran.show', $item->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                                    >
                                        Proses Pembayaran
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada transaksi menunggu pembayaran
                            </h3>

                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Transaksi akan muncul setelah penimbang menyelesaikan penimbangan dan fuzzy sudah tersedia.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if ($transaksi->hasPages())
                    <div class="mt-6">
                        {{ $transaksi->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>