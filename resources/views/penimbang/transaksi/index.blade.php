<x-layouts::app :title="'Transaksi Penimbangan'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Transaksi Penimbangan
                    </h1>

                    <p class="max-w-2xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Kelola transaksi penimbangan pelanggan. Setiap transaksi dapat memiliki banyak jenis kertas bekas,
                        dan setiap jenis kertas dapat memiliki proses penilaian masing-masing.
                    </p>
                </div>

                <div>
                    <a
                        href="{{ route('penimbang.pelanggan.index') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Pilih Pelanggan
                    </a>
                </div>
            </div>

            {{-- Session Alert --}}
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-800 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Total Transaksi
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['total'] ?? 0, 0, ',', '.') }}
                    </h2>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Semua transaksi milik penimbang ini.
                    </p>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Draft
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['draft'] ?? 0, 0, ',', '.') }}
                    </h2>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Transaksi yang sedang proses bongkar barang.
                    </p>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menunggu QC
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['menunggu_qc'] ?? 0, 0, ',', '.') }}
                    </h2>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Transaksi yang sudah siap dinilai QC.
                    </p>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Selesai
                    </p>

                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['selesai'] ?? 0, 0, ',', '.') }}
                    </h2>

                    <p class="mt-4 text-sm leading-6 text-zinc-500 dark:text-zinc-400">
                        Transaksi yang sudah selesai diproses.
                    </p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="w-full space-y-2 md:max-w-sm">
                        <label for="status" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Filter Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                            <option value="semua" @selected($status === 'semua')>Semua Status</option>
                            <option value="draft_penimbangan" @selected($status === 'draft_penimbangan')>Draft Penimbangan</option>
                            <option value="menunggu_qc" @selected($status === 'menunggu_qc')>Menunggu QC</option>
                            <option value="proses_qc" @selected($status === 'proses_qc')>Proses QC</option>
                            <option value="menunggu_pembayaran" @selected($status === 'menunggu_pembayaran')>Menunggu Pembayaran</option>
                            <option value="selesai" @selected($status === 'selesai')>Selesai</option>
                            <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Terapkan
                        </button>

                        <a
                            href="{{ route('penimbang.transaksi.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                        >
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- List transaksi --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                            Daftar Transaksi
                        </h2>

                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Data transaksi ditampilkan dalam bentuk card agar lebih mudah dibaca.
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($transaksi as $item)
                        @php
                            $statusLabel = match ($item->status) {
                                'draft_penimbangan' => 'Draft Penimbangan',
                                'menunggu_qc' => 'Menunggu QC',
                                'proses_qc' => 'Proses QC',
                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => ucfirst(str_replace('_', ' ', $item->status)),
                            };

                            $statusClass = match ($item->status) {
                                'draft_penimbangan' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'menunggu_qc' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                'proses_qc' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                'menunggu_pembayaran' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                'selesai' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                default => 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200',
                            };
                        @endphp

                        <div class="rounded-2xl border border-zinc-200 p-5 transition hover:bg-zinc-50 dark:border-zinc-800 dark:hover:bg-zinc-800/50">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                            {{ $item->kode_transaksi }}
                                        </h3>

                                        <span class="rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    <div class="grid gap-2 text-sm text-zinc-600 dark:text-zinc-400 sm:grid-cols-2">
                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Pelanggan:</span>
                                            {{ $item->nama_pelanggan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Kendaraan:</span>
                                            {{ $item->nama_kendaraan }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Jumlah Jenis Kertas:</span>
                                            {{ number_format($item->jumlah_barang, 0, ',', '.') }}
                                        </p>

                                        <p>
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Total Bersih:</span>
                                            {{ number_format($item->total_berat_bersih, 2, ',', '.') }} kg
                                        </p>
                                    </div>

                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                        Tanggal:
                                        {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row lg:flex-col">
                                    <a
                                        href="{{ route('penimbang.transaksi.show', $item->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                                    >
                                        Lihat Detail
                                    </a>

                                 @if (in_array($item->status, ['draft_penimbangan', 'proses_qc', 'menunggu_qc']))
                                    <a
                                        href="{{ route('penimbang.transaksi.timbangan-kedua', $item->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                                    >
                                        Timbangan Kedua
                                    </a>
                                @endif

                                @if ($item->status === 'menunggu_pembayaran')
                                    <span class="inline-flex items-center justify-center rounded-xl border border-orange-200 bg-orange-50 px-4 py-2 text-sm font-semibold text-orange-700 dark:border-orange-900/40 dark:bg-orange-900/20 dark:text-orange-300">
                                        Menunggu Kasir
                                    </span>
                                @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-zinc-500 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                </svg>
                            </div>

                            <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada transaksi
                            </h3>

                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Transaksi penimbangan akan muncul di halaman ini setelah pelanggan dipilih.
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