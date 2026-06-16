<x-layouts::app :title="'Detail Pembayaran'">
    <div class="mx-auto max-w-7xl space-y-8 px-6 py-8 lg:px-8">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="space-y-2">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Menu Kasir</p>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Detail Pembayaran</h1>
                <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                    Detail transaksi pembayaran dan rincian barang.
                </p>
            </div>

            <div class="flex gap-3">
                <a
                    href="{{ route('kasir.laporan.print', $pembayaran->id) }}"
                    target="_blank"
                    class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 dark:bg-teal-600 dark:text-white dark:hover:bg-teal-700"
                >
                    Print
                </a>

                <a
                    href="{{ route('kasir.laporan.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>
        </div>

        {{-- Data Transaksi --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Data Transaksi</h2>

            <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Kode Pembayaran</p>
                    <p class="mt-1 font-semibold text-zinc-900 dark:text-white">{{ $pembayaran->kode_pembayaran }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Kode Transaksi</p>
                    <p class="mt-1 font-semibold text-zinc-900 dark:text-white">{{ $pembayaran->kode_transaksi }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Pelanggan</p>
                    <p class="mt-1 font-semibold text-zinc-900 dark:text-white">{{ $pembayaran->nama_pelanggan }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Kendaraan</p>
                    <p class="mt-1 font-semibold text-zinc-900 dark:text-white">{{ $pembayaran->nama_kendaraan }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Plat Kendaraan</p>
                    <p class="mt-1 font-semibold text-zinc-900 dark:text-white">{{ $pembayaran->plat_kendaraan ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Tanggal Bayar</p>
                    <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Rincian Barang --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">Rincian Barang</h2>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 text-left dark:border-zinc-800">
                            <th class="px-2 py-3 font-semibold text-zinc-700 dark:text-zinc-300">Jenis Kertas</th>
                            <th class="px-2 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Berat Bersih</th>
                            <th class="px-2 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Potongan</th>
                            <th class="px-2 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Berat Layak</th>
                            <th class="px-2 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Harga / Kg</th>
                            <th class="px-2 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailBarang as $item)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                <td class="px-2 py-3 font-semibold text-zinc-900 dark:text-white">{{ $item->nama_barang }}</td>
                                <td class="tabular-nums px-2 py-3 text-right text-zinc-600 dark:text-zinc-400">{{ number_format($item->berat_bersih, 2, ',', '.') }} kg</td>
                                <td class="tabular-nums px-2 py-3 text-right text-zinc-600 dark:text-zinc-400">
                                    {{ number_format($item->persentase_potongan, 2, ',', '.') }}%
                                    /
                                    {{ number_format($item->potongan_berat, 2, ',', '.') }} kg
                                </td>
                                <td class="tabular-nums px-2 py-3 text-right text-zinc-600 dark:text-zinc-400">{{ number_format($item->berat_layak, 2, ',', '.') }} kg</td>
                                <td class="tabular-nums px-2 py-3 text-right text-zinc-600 dark:text-zinc-400">Rp{{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                                <td class="tabular-nums px-2 py-3 text-right font-semibold text-teal-700 dark:text-teal-400">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Ringkasan Pembayaran --}}
        <div class="grid gap-5 md:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Transaksi</p>
                <p class="mt-2 tabular-nums text-2xl font-bold text-zinc-900 dark:text-white">
                    Rp{{ number_format($pembayaran->total_transaksi, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20">
                <p class="text-sm text-amber-700 dark:text-amber-300">Potongan Kasbon</p>
                <p class="mt-2 tabular-nums text-2xl font-bold text-amber-900 dark:text-amber-100">
                    Rp{{ number_format($pembayaran->potongan_kasbon, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl border border-teal-200 bg-teal-50 p-6 shadow-sm dark:border-teal-900/40 dark:bg-teal-900/20">
                <p class="text-sm text-teal-700 dark:text-teal-300">Dibayar ke Pelanggan</p>
                <p class="mt-2 tabular-nums text-2xl font-bold text-teal-900 dark:text-teal-100">
                    Rp{{ number_format($pembayaran->total_dibayar_ke_pelanggan, 0, ',', '.') }}
                </p>
            </div>
        </div>

    </div>
</x-layouts::app>