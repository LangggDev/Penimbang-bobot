<x-layouts::app :title="'Laporan Kasir'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Kasir
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Laporan Pembayaran
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Rekap pembayaran transaksi, berat layak, total transaksi, potongan kasbon, dan total dibayar ke pelanggan.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 print:hidden dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                >
                    Cetak Laporan
                </button>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm print:hidden dark:border-zinc-800 dark:bg-zinc-900">
                <form method="GET" class="grid gap-4 md:grid-cols-[1fr_1fr_auto_auto] md:items-end">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Tanggal Awal
                        </label>

                        <input
                            type="date"
                            name="tanggal_awal"
                            value="{{ $tanggalAwal }}"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            name="tanggal_akhir"
                            value="{{ $tanggalAkhir }}"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Terapkan
                    </button>

                    <a
                        href="{{ route('kasir.laporan.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                    >
                        Reset
                    </a>
                </form>
            </div>

            <div class="hidden print:block">
                <h1 class="text-2xl font-bold">
                    Laporan Pembayaran Kasir
                </h1>

                <p class="mt-1 text-sm">
                    Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }}
                    sampai {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
                </p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Pembayaran</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary->total_pembayaran, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Berat Layak</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary->total_berat_layak, 2, ',', '.') }} kg
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Transaksi</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        Rp{{ number_format($summary->total_transaksi, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Dibayar ke Pelanggan</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        Rp{{ number_format($summary->total_dibayar_ke_pelanggan, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Detail Pembayaran
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 print:hidden dark:text-zinc-400">
                        Data pembayaran berdasarkan periode tanggal yang dipilih.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w -[1100px] border-collapse text-left text-sm">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800">
                                <th class="px-4 py-3 font-semibold text-zinc-700 dark:text-zinc-300">Tanggal</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700 dark:text-zinc-300">Kode Bayar</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700 dark:text-zinc-300">Transaksi</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700 dark:text-zinc-300">Pelanggan</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Berat Layak</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Total Transaksi</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Potongan Kasbon</th>
                                <th class="px-4 py-3 text-right font-semibold text-zinc-700 dark:text-zinc-300">Dibayar</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700 dark:text-zinc-300">Metode</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pembayaran as $item)
                                <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                    <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                        {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-4 py-4 font-medium text-zinc-900 dark:text-white">
                                        {{ $item->kode_pembayaran }}
                                    </td>

                                    <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                        {{ $item->kode_transaksi }}
                                    </td>

                                    <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                        {{ $item->nama_pelanggan }}
                                    </td>

                                    <td class="px-4 py-4 text-right text-zinc-600 dark:text-zinc-400">
                                        {{ number_format($item->total_berat_layak, 2, ',', '.') }} kg
                                    </td>

                                    <td class="px-4 py-4 text-right text-zinc-600 dark:text-zinc-400">
                                        Rp{{ number_format($item->total_transaksi, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-4 text-right text-zinc-600 dark:text-zinc-400">
                                        Rp{{ number_format($item->potongan_kasbon, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-4 text-right font-semibold text-zinc-900 dark:text-white">
                                        Rp{{ number_format($item->total_dibayar_ke_pelanggan, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-4 text-zinc-600 dark:text-zinc-400">
                                        {{ ucfirst($item->metode_pembayaran) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-14 text-center text-zinc-500 dark:text-zinc-400">
                                        Belum ada data pembayaran pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr class="border-t border-zinc-300 font-semibold dark:border-zinc-700">
                                <td colspan="4" class="px-4 py-4 text-zinc-900 dark:text-white">
                                    Total
                                </td>

                                <td class="px-4 py-4 text-right text-zinc-900 dark:text-white">
                                    {{ number_format($summary->total_berat_layak, 2, ',', '.') }} kg
                                </td>

                                <td class="px-4 py-4 text-right text-zinc-900 dark:text-white">
                                    Rp{{ number_format($summary->total_transaksi, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4 text-right text-zinc-900 dark:text-white">
                                    Rp{{ number_format($summary->total_potongan_kasbon, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4 text-right text-zinc-900 dark:text-white">
                                    Rp{{ number_format($summary->total_dibayar_ke_pelanggan, 0, ',', '.') }}
                                </td>

                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($pembayaran->hasPages())
                    <div class="mt-6 print:hidden">
                        {{ $pembayaran->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-layouts::app>

