<x-layouts::app :title="'Detail Pembayaran'">
    <div class="mx-auto max-w-7xl space-y-8 px-6 py-8 lg:px-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm text-zinc-500">Menu Kasir</p>
                <h1 class="text-3xl font-bold text-zinc-900">Detail Pembayaran</h1>
                <p class="mt-2 text-zinc-600">
                    Detail transaksi pembayaran dan rincian barang.
                </p>
            </div>

            <div class="flex gap-3">
                <a
                    href="{{ route('kasir.laporan.print', $pembayaran->id) }}"
                    target="_blank"
                    class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white hover:bg-zinc-700"
                >
                    Print
                </a>

                <a
                    href="{{ route('kasir.laporan.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-900 hover:bg-zinc-50"
                >
                    Kembali
                </a>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-zinc-900">Data Transaksi</h2>

            <div class="mt-6 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <p class="text-sm text-zinc-500">Kode Pembayaran</p>
                    <p class="font-semibold">{{ $pembayaran->kode_pembayaran }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">Kode Transaksi</p>
                    <p class="font-semibold">{{ $pembayaran->kode_transaksi }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">Pelanggan</p>
                    <p class="font-semibold">{{ $pembayaran->nama_pelanggan }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">Kendaraan</p>
                    <p class="font-semibold">{{ $pembayaran->nama_kendaraan }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">Plat Kendaraan</p>
                    <p class="font-semibold">{{ $pembayaran->plat_kendaraan ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-sm text-zinc-500">Tanggal Bayar</p>
                    <p class="font-semibold">
                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-zinc-900">Rincian Barang</h2>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-3">Jenis Kertas</th>
                            <th class="py-3">Berat Bersih</th>
                            <th class="py-3">Potongan</th>
                            <th class="py-3">Berat Layak</th>
                            <th class="py-3">Harga / Kg</th>
                            <th class="py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailBarang as $item)
                            <tr class="border-b">
                                <td class="py-3 font-semibold">{{ $item->nama_barang }}</td>
                                <td class="py-3">{{ number_format($item->berat_bersih, 2, ',', '.') }} kg</td>
                                <td class="py-3">
                                    {{ number_format($item->persentase_potongan, 2, ',', '.') }}%
                                    /
                                    {{ number_format($item->potongan_berat, 2, ',', '.') }} kg
                                </td>
                                <td class="py-3">{{ number_format($item->berat_layak, 2, ',', '.') }} kg</td>
                                <td class="py-3">Rp{{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                                <td class="py-3 font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-zinc-500">Total Transaksi</p>
                <p class="mt-2 text-2xl font-bold">
                    Rp{{ number_format($pembayaran->total_transaksi, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-zinc-500">Potongan Kasbon</p>
                <p class="mt-2 text-2xl font-bold">
                    Rp{{ number_format($pembayaran->potongan_kasbon, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
                <p class="text-sm text-zinc-500">Dibayar ke Pelanggan</p>
                <p class="mt-2 text-2xl font-bold">
                    Rp{{ number_format($pembayaran->total_dibayar_ke_pelanggan, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
</x-layouts::app>