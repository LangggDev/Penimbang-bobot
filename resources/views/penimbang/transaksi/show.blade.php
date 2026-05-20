<x-layouts::app :title="'Detail Transaksi'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Detail Transaksi
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Menampilkan detail transaksi, hasil timbang bertahap, berat bersih per jenis kertas, dan hasil penilaian QC.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    @if (in_array($transaksi->status, ['draft_penimbangan', 'proses_qc', 'menunggu_qc']))
                        <a
                            href="{{ route('penimbang.transaksi.timbangan-kedua', $transaksi->id) }}"
                            class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Timbang Bertahap
                        </a>
                    @endif

                    <a
                        href="{{ route('penimbang.transaksi.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                    >
                        Kembali
                    </a>
                </div>
            </div>

            {{-- Informasi transaksi --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kode Transaksi</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->kode_transaksi }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Tanggal</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d F Y, H:i') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Status</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ ucfirst(str_replace('_', ' ', $transaksi->status)) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pelanggan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->nama_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">No HP</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->no_hp ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Alamat</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->alamat ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Kendaraan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->nama_kendaraan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Plat Kendaraan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->plat_kendaraan ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Timbang Awal</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ number_format($transaksi->berat_timbang_pertama, 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Berat Kendaraan Akhir</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ number_format($transaksi->berat_timbang_kedua, 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Catatan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $transaksi->catatan ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Jenis Kertas</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['jumlah_jenis'], 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Total Bersih</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['total_berat_bersih'], 2, ',', '.') }} kg
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Sudah QC</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['sudah_qc'], 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Belum QC</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['belum_qc'], 0, ',', '.') }}
                    </h2>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Jumlah Timbang</p>
                    <h2 class="mt-3 text-3xl font-bold text-zinc-900 dark:text-white">
                        {{ number_format($summary['jumlah_timbang'], 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            {{-- Detail jenis kertas --}}
            <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Detail Jenis Kertas
                    </h2>

                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                        Berat bersih diambil dari hasil timbang bertahap. Nilai kualitas diambil dari penilaian QC.
                    </p>
                </div>

                <div class="space-y-5">
                    @forelse ($detailBarang as $detail)
                        @php
                            $riwayatDetail = $riwayatByDetail->get($detail->detail_id, collect());
                        @endphp

                        <div class="rounded-2xl border border-zinc-200 p-5 dark:border-zinc-800">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                            {{ $detail->nama_barang }}
                                        </h3>

                                        <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                            {{ $detail->kode_barang }}
                                        </span>

                                        @if ($detail->total_berat_bersih > 0)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                Sudah Ditimbang
                                            </span>
                                        @else
                                            <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                Belum Ditimbang
                                            </span>
                                        @endif

                                        @if ($detail->status_qc === 'sudah_dinilai')
                                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                QC Sudah Dinilai
                                            </span>
                                        @else
                                            <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                                QC Belum Dinilai
                                            </span>
                                        @endif
                                    </div>

                                    <div class="grid gap-3 text-sm text-zinc-600 dark:text-zinc-400 sm:grid-cols-2 lg:grid-cols-4">
                                        <div>
                                            <p class="text-zinc-500 dark:text-zinc-400">Sebelum Bongkar</p>
                                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                {{ number_format($detail->total_berat_kotor, 2, ',', '.') }} kg
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-zinc-500 dark:text-zinc-400">Sisa Setelah Bongkar</p>
                                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                {{ number_format($detail->total_tara, 2, ',', '.') }} kg
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-zinc-500 dark:text-zinc-400">Berat Bersih</p>
                                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                {{ number_format($detail->total_berat_bersih, 2, ',', '.') }} kg
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-zinc-500 dark:text-zinc-400">Nilai Kualitas QC</p>
                                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                {{ $detail->nilai_kualitas_kertas !== null ? number_format($detail->nilai_kualitas_kertas, 2, ',', '.') : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($detail->catatan_qc)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                            <span class="font-medium text-zinc-800 dark:text-zinc-200">Catatan QC:</span>
                                            {{ $detail->catatan_qc }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 rounded-2xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                <h4 class="text-sm font-semibold text-zinc-900 dark:text-white">
                                    Riwayat Timbang Barang Ini
                                </h4>

                                <div class="mt-3 space-y-3">
                                    @forelse ($riwayatDetail as $riwayat)
                                        <div class="grid gap-3 rounded-xl border border-zinc-200 bg-white p-4 text-sm dark:border-zinc-800 dark:bg-zinc-900 sm:grid-cols-4">
                                            <div>
                                                <p class="text-zinc-500 dark:text-zinc-400">Timbang Ke</p>
                                                <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                    {{ $riwayat->urutan_timbang }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-zinc-500 dark:text-zinc-400">Sebelum</p>
                                                <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                    {{ number_format($riwayat->berat_kotor, 2, ',', '.') }} kg
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-zinc-500 dark:text-zinc-400">Setelah</p>
                                                <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                    {{ number_format($riwayat->tara, 2, ',', '.') }} kg
                                                </p>
                                            </div>

                                            <div>
                                                <p class="text-zinc-500 dark:text-zinc-400">Berat Barang</p>
                                                <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                    {{ number_format($riwayat->berat_bersih, 2, ',', '.') }} kg
                                                </p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            Belum ada riwayat timbang untuk jenis kertas ini.
                                        </p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                Belum ada detail jenis kertas
                            </h3>

                            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                Detail akan muncul setelah transaksi dibuat dari timbangan pertama.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>