<x-layouts::app :title="'Timbang Bertahap'">
    <div class="px-4 py-4 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-6xl space-y-8">

            @php
                $isSingleItem = $detailBarang->count() === 1;
            @endphp

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu Penimbang
                    </p>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl dark:text-white">
                        @if ($isSingleItem)
                            Proses Timbang &mdash; Single Item
                        @else
                            Proses Bongkar &amp; Timbang Bertahap
                        @endif
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        @if ($isSingleItem)
                            Transaksi ini hanya memiliki <strong>1 jenis kertas</strong>.
                            Masukkan berat kendaraan akhir (timbangan kedua).
                            Berat bersih dihitung dari selisih timbang pertama dan timbangan kedua.
                        @else
                            Digunakan ketika pelanggan membawa lebih dari satu jenis kertas bekas.
                            Setiap selesai bongkar satu jenis kertas, kendaraan ditimbang ulang.
                            Berat jenis kertas dihitung dari selisih berat sebelumnya dengan berat setelah bongkar.
                        @endif
                    </p>
                </div>

                <a
                    href="{{ route('penimbang.transaksi.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            {{-- Alert --}}
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

            {{-- Informasi transaksi --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-t-4 border-t-teal-600">
                <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Kode Transaksi</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $transaksi->kode_transaksi }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Pelanggan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $transaksi->nama_pelanggan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Kendaraan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $transaksi->nama_kendaraan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Plat Kendaraan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $transaksi->plat_kendaraan ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Timbang Awal</p>
                        <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format($transaksi->berat_timbang_pertama, 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-500 dark:text-zinc-400">Berat Terakhir</p>
                        <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format($beratTerakhir, 2, ',', '.') }} kg
                        </p>
                    </div>
                </div>
            </div>

            {{-- Ringkasan --}}
            <div class="grid gap-5 md:grid-cols-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-l-4 border-l-teal-600">
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Jenis Kertas</p>
                    <h2 class="mt-3 tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                        {{ $detailBarang->count() }}
                    </h2>
                    <p class="mt-3 text-sm text-slate-500 dark:text-zinc-400">Total jenis kertas dalam transaksi.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-l-4 border-l-green-600">
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Sudah Ditimbang</p>
                    <h2 class="mt-3 tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                        {{ $riwayatTimbang->count() }}
                    </h2>
                    <p class="mt-3 text-sm text-slate-500 dark:text-zinc-400">Jenis kertas yang sudah selesai dibongkar.</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-l-4 border-l-teal-400">
                    <p class="text-sm font-medium text-slate-500 dark:text-zinc-400">Total Berat Bersih</p>
                    <h2 class="mt-3 tabular-nums text-2xl font-bold text-slate-900 sm:text-3xl dark:text-white">
                        {{ number_format($totalBeratBersih, 2, ',', '.') }} kg
                    </h2>
                    <p class="mt-3 text-sm text-slate-500 dark:text-zinc-400">Akumulasi hasil bongkar bertahap.</p>
                </div>
            </div>

            {{-- Catatan konsep --}}
            <div class="rounded-2xl border border-teal-100 bg-teal-50 p-5 dark:border-teal-900/30 dark:bg-teal-900/10">
                <h2 class="text-sm font-semibold text-teal-800 dark:text-teal-300">
                    @if ($isSingleItem)
                        Cara Hitung Timbang Single Item
                    @else
                        Cara Hitung Timbang Bertahap
                    @endif
                </h2>

                <p class="mt-2 text-sm leading-6 text-teal-700 dark:text-teal-400">
                    @if ($isSingleItem)
                        Contoh: timbang awal 1650 kg. Setelah bongkar, kendaraan ditimbang ulang menjadi 1180 kg.
                        Berat bersih = 1650 &minus; 1180 = <strong>470 kg</strong>.
                    @else
                        Contoh: timbang awal 2200 kg. Setelah bongkar SWL (200 kg), sisa 2000 kg.
                        Setelah bongkar Duplex (300 kg), sisa 1700 kg. Setelah bongkar Box (600 kg), sisa 1100 kg.
                    @endif
                </p>
            </div>

            {{-- Form timbang bertahap --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        @if ($isSingleItem)
                            Input Timbangan Kedua
                        @else
                            Input Bongkar Berikutnya
                        @endif
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        @if ($isSingleItem)
                            Pilih jenis kertas, lalu masukkan berat kendaraan akhir (timbangan kedua).
                            Sistem akan menghitung berat bersih secara otomatis.
                        @else
                            Pilih jenis kertas yang baru selesai dibongkar, lalu input berat bersih barang yang dibongkar.
                        @endif
                    </p>
                </div>

                @if ($detailBelumDitimbang->count() > 0)
                    <form
                        method="POST"
                        action="{{ route('penimbang.transaksi.timbang-bertahap.store', $transaksi->id) }}"
                        class="space-y-6"
                    >
                        @csrf

                        {{-- Hidden values for JS preview --}}
                        <input type="hidden" id="berat_timbang_pertama" value="{{ $transaksi->berat_timbang_pertama }}">
                        <input type="hidden" id="berat_sebelumnya" value="{{ $beratTerakhir }}">
                        <input type="hidden" id="is_single_item" value="{{ $isSingleItem ? '1' : '0' }}">

                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="space-y-2">
                                <label for="detail_transaksi_barang_id" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                    Jenis Kertas yang Dibongkar
                                </label>

                                <select
                                    id="detail_transaksi_barang_id"
                                    name="detail_transaksi_barang_id"
                                    required
                                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                                >
                                    <option value="">Pilih jenis kertas</option>
                                    @foreach ($detailBelumDitimbang as $detail)
                                        <option value="{{ $detail->detail_id }}" @selected(old('detail_transaksi_barang_id') == $detail->detail_id)>
                                            {{ $detail->nama_barang }} - {{ $detail->kode_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                @if ($isSingleItem)
                                    {{-- SINGLE ITEM: input berat kendaraan akhir --}}
                                    <label for="berat_kendaraan_akhir" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                        Berat Kendaraan Akhir / Timbangan Kedua
                                    </label>

                                    <div class="relative">
                                        <input
                                            id="berat_kendaraan_akhir"
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            name="berat_kendaraan_akhir"
                                            value="{{ old('berat_kendaraan_akhir') }}"
                                            placeholder="Contoh: 1180"
                                            required
                                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                                        >
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">kg</span>
                                    </div>

                                    <p class="text-sm text-slate-500 dark:text-zinc-400">
                                        Masukkan berat kendaraan akhir. Berat bersih = timbang pertama
                                        ({{ number_format($transaksi->berat_timbang_pertama, 2, ',', '.') }} kg) &minus; nilai ini.
                                    </p>
                                @else
                                    {{-- MULTI ITEM: input berat barang dibongkar --}}
                                    <label for="berat_barang_dibongkar" class="text-sm font-medium text-slate-700 dark:text-zinc-300">
                                        Berat Bersih Barang yang Dibongkar
                                    </label>

                                    <div class="relative">
                                        <input
                                            id="berat_barang_dibongkar"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            name="berat_barang_dibongkar"
                                            value="{{ old('berat_barang_dibongkar') }}"
                                            placeholder="Contoh: 200"
                                            required
                                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                                        >
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-500">kg</span>
                                    </div>

                                    <p class="text-sm text-slate-500 dark:text-zinc-400">
                                        Masukkan berat bersih barang yang selesai dibongkar. Sistem akan menghitung sisa berat sebagai acuan timbang berikutnya.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Preview kalkulasi --}}
                        <div class="rounded-2xl border border-teal-100 bg-teal-50 p-5 dark:border-teal-900/30 dark:bg-teal-900/10 grid gap-4 sm:grid-cols-2">
                            <div>
                                <p class="text-sm font-semibold text-teal-800 dark:text-teal-300">Preview berat bersih barang:</p>
                                <p class="mt-2 text-2xl font-bold text-teal-600 dark:text-teal-400 tabular-nums">
                                    <span id="preview_berat_bersih">0,00</span> kg
                                </p>
                                <p class="mt-2 text-sm text-teal-500 dark:text-teal-400/80">
                                    @if ($isSingleItem)
                                        Hasil = timbang pertama &minus; berat kendaraan akhir.
                                    @else
                                        Sesuai dengan angka yang Anda input.
                                    @endif
                                </p>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-teal-800 dark:text-teal-300">
                                    @if ($isSingleItem)
                                        Berat kendaraan akhir:
                                    @else
                                        Sisa berat setelah bongkar:
                                    @endif
                                </p>
                                <p class="mt-2 text-2xl font-bold text-teal-600 dark:text-teal-400 tabular-nums">
                                    <span id="preview_sisa_berat">0,00</span> kg
                                </p>
                                <p class="mt-2 text-sm text-teal-500 dark:text-teal-400/80">
                                    @if ($isSingleItem)
                                        Nilai yang Anda input sebagai timbangan kedua.
                                    @else
                                        Rumus: berat sebelumnya ({{ number_format($beratTerakhir, 2, ',', '.') }} kg) &minus; berat bersih barang.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="catatan" class="text-sm font-medium text-slate-700 dark:text-zinc-300">Catatan</label>
                            <textarea
                                id="catatan"
                                name="catatan"
                                rows="3"
                                placeholder="Opsional, misalnya: bongkar duplex selesai"
                                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                            >{{ old('catatan') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                            >
                                Simpan Timbang Bertahap
                            </button>
                        </div>
                    </form>
                @else
                    <div class="rounded-2xl border border-green-200 bg-green-50 p-5 text-green-900 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-200">
                        <h3 class="font-semibold text-green-800 dark:text-green-300">
                            Semua jenis kertas sudah ditimbang.
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-green-700 dark:text-green-400">
                            Timbangan terakhir dianggap sebagai berat kendaraan kosong.
                            Jika data sudah benar, selesaikan penimbangan untuk melanjutkan transaksi ke tahap pembayaran.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('penimbang.transaksi.selesai-penimbangan', $transaksi->id) }}"
                            class="mt-5"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700"
                            >
                                Selesaikan Penimbangan
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Riwayat timbang --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Riwayat Timbang Bertahap</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Setiap baris menunjukkan hasil bongkar satu jenis kertas.</p>
                </div>

                <div class="space-y-4">
                    @forelse ($riwayatTimbang as $riwayat)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-zinc-800 bg-white">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between bg-white dark:bg-zinc-900">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                            Timbang {{ $riwayat->urutan_timbang }} - {{ $riwayat->nama_barang }}
                                        </h3>
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                            Selesai
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kode: {{ $riwayat->kode_barang }}</p>
                                    @if ($riwayat->catatan)
                                        <p class="mt-2 text-sm text-slate-600 dark:text-zinc-400">Catatan: {{ $riwayat->catatan }}</p>
                                    @endif
                                </div>

                                <div class="grid gap-3 text-sm sm:grid-cols-3 lg:min-w-[520px]">
                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                                        <p class="text-slate-500 dark:text-zinc-400">
                                            @if ($isSingleItem) Timbang Pertama @else Sebelum Bongkar @endif
                                        </p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format($riwayat->berat_kotor, 2, ',', '.') }} kg
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                                        <p class="text-slate-500 dark:text-zinc-400">
                                            @if ($isSingleItem) Timbang Kedua @else Setelah Bongkar @endif
                                        </p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format($riwayat->tara, 2, ',', '.') }} kg
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm border-l-2 border-l-teal-600">
                                        <p class="text-slate-500 dark:text-zinc-400">Berat Bersih</p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format($riwayat->berat_bersih, 2, ',', '.') }} kg
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Belum ada riwayat timbang</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Riwayat akan muncul setelah proses bongkar pertama disimpan.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Daftar detail barang --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Daftar Jenis Kertas</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Total berat bersih akan terisi otomatis setelah jenis kertas selesai ditimbang.
                    </p>
                </div>

                <div class="space-y-3">
                    @foreach ($detailBarang as $detail)
                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-zinc-800 bg-white">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between bg-white dark:bg-zinc-900">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="font-semibold text-slate-900 dark:text-white">{{ $detail->nama_barang }}</h3>

                                        @if ($detail->total_berat_bersih > 0)
                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                Sudah Ditimbang
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                Belum Ditimbang
                                            </span>
                                        @endif

                                        <span class="rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $detail->status_qc === 'sudah_dinilai'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                                : 'bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-300' }}">
                                            QC: {{ ucfirst(str_replace('_', ' ', $detail->status_qc)) }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">Kode: {{ $detail->kode_barang }}</p>
                                </div>

                                <div class="text-left md:text-right">
                                    <p class="text-sm text-slate-500 dark:text-zinc-400">Berat Bersih</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900 dark:text-white tabular-nums">
                                        {{ number_format($detail->total_berat_bersih, 2, ',', '.') }} kg
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
    const isSingleItem        = document.getElementById('is_single_item')?.value === '1';
    const beratTimbangPertama = parseFloat(document.getElementById('berat_timbang_pertama')?.value || 0);
    const beratSebelumnya     = parseFloat(document.getElementById('berat_sebelumnya')?.value || 0);
    const previewBeratBersih  = document.getElementById('preview_berat_bersih');
    const previewSisaBerat    = document.getElementById('preview_sisa_berat');

    // Active input differs by mode
    const inputSingle = document.getElementById('berat_kendaraan_akhir');
    const inputMulti  = document.getElementById('berat_barang_dibongkar');
    const activeInput = isSingleItem ? inputSingle : inputMulti;

    function formatNumberId(value) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    }

    function updatePreviewTimbang() {
        if (!activeInput || !previewBeratBersih || !previewSisaBerat) return;

        const inputVal = parseFloat(activeInput.value || 0);

        if (isSingleItem) {
            // Mode single item:
            // preview kiri  = berat_bersih = berat_timbang_pertama - input
            // preview kanan = berat_kendaraan_akhir = nilai input
            if (inputVal >= beratTimbangPertama) {
                activeInput.setCustomValidity(
                    'Berat kendaraan akhir harus lebih kecil dari berat timbang pertama (' + formatNumberId(beratTimbangPertama) + ' kg).'
                );
            } else {
                activeInput.setCustomValidity('');
            }
            const beratBersih = Math.max(beratTimbangPertama - inputVal, 0);
            previewBeratBersih.textContent = formatNumberId(beratBersih);
            previewSisaBerat.textContent   = formatNumberId(inputVal);

        } else {
            // Mode multi item:
            // preview kiri  = berat_bersih = nilai input
            // preview kanan = sisa_berat   = berat_sebelumnya - input
            if (inputVal > beratSebelumnya) {
                activeInput.setCustomValidity(
                    'Berat bersih barang tidak boleh lebih besar dari berat sebelumnya (' + formatNumberId(beratSebelumnya) + ' kg).'
                );
            } else {
                activeInput.setCustomValidity('');
            }
            previewBeratBersih.textContent = formatNumberId(inputVal);
            const sisaBerat = Math.max(beratSebelumnya - inputVal, 0);
            previewSisaBerat.textContent   = formatNumberId(sisaBerat);
        }
    }

    if (activeInput) {
        activeInput.addEventListener('input', updatePreviewTimbang);
        updatePreviewTimbang();
    }
    </script>
</x-layouts::app>