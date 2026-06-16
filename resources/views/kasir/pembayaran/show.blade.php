<x-layouts::app :title="'Detail Pembayaran'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                        Menu Kasir
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Detail Pembayaran
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-zinc-600 dark:text-zinc-400">
                        Input harga per kg untuk setiap jenis barang. Sistem akan menghitung total pembayaran dari berat layak hasil fuzzy.
                    </p>
                </div>

                <a
                    href="{{ route('kasir.pembayaran.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-zinc-300 px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Data Transaksi
                    </h2>

                    <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Kode Transaksi</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                {{ $transaksi->kode_transaksi }}
                            </p>
                        </div>

                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Pelanggan</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                {{ $transaksi->nama_pelanggan }}
                            </p>
                        </div>

                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Kendaraan</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                {{ $transaksi->nama_kendaraan }}
                            </p>
                        </div>

                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Plat Kendaraan</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                {{ $transaksi->plat_kendaraan ?: '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                        Ringkasan Berat
                    </h2>

                    <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Jumlah Barang</p>
                            <p class="mt-1 tabular-nums font-semibold text-zinc-900 dark:text-white">
                                {{ $summary['jumlah_barang'] }}
                            </p>
                        </div>

                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Total Berat Bersih</p>
                            <p class="mt-1 tabular-nums font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($summary['total_berat_bersih'], 2, ',', '.') }} kg
                            </p>
                        </div>

                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Total Potongan Berat</p>
                            <p class="mt-1 tabular-nums font-semibold text-zinc-900 dark:text-white">
                                {{ number_format($summary['total_potongan_berat'], 2, ',', '.') }} kg
                            </p>
                        </div>

                        <div>
                            <p class="text-zinc-500 dark:text-zinc-400">Total Berat Layak</p>
                            <p class="mt-1 tabular-nums font-semibold text-teal-700 dark:text-teal-400">
                                {{ number_format($summary['total_berat_layak'], 2, ',', '.') }} kg
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-teal-200 bg-teal-50 p-5 text-teal-900 dark:border-teal-900/40 dark:bg-teal-900/20 dark:text-teal-200">
                <h2 class="text-sm font-semibold">
                    Rumus Pembayaran
                </h2>

                <p class="mt-2 text-sm leading-6">
                    Rumus lapangan:
                    <span class="font-semibold">Berat Bersih - Bobot Ketidaklayakan (%) = Berat Layak</span>.
                    Lalu pembayaran dihitung dengan:
                    <span class="font-semibold">Berat Layak × Harga per Kg = Subtotal</span>.
                </p>
            </div>

            @if (!$siapBayar)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-200">
                    <h2 class="text-sm font-semibold">
                        Pembayaran Belum Bisa Diproses
                    </h2>

                    <p class="mt-2 text-sm leading-6">
                        Masih ada barang yang belum memiliki hasil fuzzy. Pastikan semua barang sudah dinilai QC dan fuzzy berhasil dihitung.
                    </p>
                </div>
            @endif

            <form method="POST" action="{{ route('kasir.pembayaran.store', $transaksi->id) }}" class="space-y-8">
                @csrf

                {{-- RINCIAN BARANG --}}
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                            Rincian Barang
                        </h2>

                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Masukkan harga per kg pada setiap jenis barang.
                        </p>
                    </div>

                    <div class="space-y-5">
                        @forelse ($detailBarang as $item)
                            <div class="rounded-2xl border border-zinc-200 p-5 dark:border-zinc-800">
                                <div class="grid gap-5 lg:grid-cols-[1fr_260px] lg:items-start">
                                    <div class="space-y-4">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                                {{ $item->nama_barang }}
                                            </h3>

                                            <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                                {{ $item->kode_barang }}
                                            </span>

                                           @if ((int) $item->bypass_fuzzy === 1)
                                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    Tanpa Fuzzy ≤ 100 kg
                                                </span>
                                            @elseif ($item->fuzzy_id)
                                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                    Fuzzy Ada
                                                </span>
                                            @else
                                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                    Fuzzy Belum Ada
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
                                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                                <p class="text-zinc-500 dark:text-zinc-400">Berat Bersih</p>
                                                <p class="mt-1 tabular-nums font-semibold text-zinc-900 dark:text-white">
                                                    {{ number_format($item->total_berat_bersih, 2, ',', '.') }} kg
                                                </p>
                                            </div>

                                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                                <p class="text-zinc-500 dark:text-zinc-400">Bobot Ketidaklayakan</p>
                                                <p class="mt-1 tabular-nums font-semibold text-zinc-900 dark:text-white">
                                                    -{{ number_format($item->persentase_potongan ?? 0, 2, ',', '.') }}%
                                                </p>
                                            </div>

                                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                                <p class="text-zinc-500 dark:text-zinc-400">Potongan Berat</p>
                                                <p class="mt-1 tabular-nums font-semibold text-zinc-900 dark:text-white">
                                                    {{ number_format($item->potongan_berat ?? 0, 2, ',', '.') }} kg
                                                </p>
                                            </div>

                                            <div class="rounded-xl bg-teal-50 p-4 dark:bg-teal-900/20">
                                                <p class="text-teal-700 dark:text-teal-300">Berat Layak</p>
                                                <p class="mt-1 tabular-nums font-semibold text-teal-950 dark:text-teal-100">
                                                    {{ number_format($item->berat_layak ?? 0, 2, ',', '.') }} kg
                                                </p>
                                            </div>
                                        </div>

                                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm dark:border-zinc-800 dark:bg-zinc-950">
                                            <p class="text-zinc-600 dark:text-zinc-400">
                                                Perhitungan:
                                                <span class="tabular-nums font-semibold text-zinc-900 dark:text-white">
                                                    {{ number_format($item->total_berat_bersih, 2, ',', '.') }} kg
                                                    - {{ number_format($item->persentase_potongan ?? 0, 2, ',', '.') }}%
                                                    =
                                                    {{ number_format($item->berat_layak ?? 0, 2, ',', '.') }} kg
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                                Harga per Kg
                                            </label>

                                            <div class="relative">
                                                <input
                                                    type="number"
                                                    step="1"
                                                    min="1"
                                                    name="harga_per_kg[{{ $item->detail_id }}]"
                                                    value="{{ old('harga_per_kg.' . $item->detail_id) }}"
                                                    data-harga-input
                                                    data-berat-layak="{{ $item->berat_layak ?? 0 }}"
                                                    data-subtotal-target="subtotal-{{ $item->detail_id }}"
                                                    placeholder="Contoh: 2000"
                                                    {{ !$siapBayar ? 'disabled' : '' }}
                                                    required
                                                    class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 pl-12 text-sm text-zinc-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500/20 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:disabled:bg-zinc-800"
                                                >

                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-zinc-500">
                                                    Rp
                                                </span>
                                            </div>
                                        </div>

                                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                                Subtotal
                                            </p>

                                            <p
                                                id="subtotal-{{ $item->detail_id }}"
                                                class="mt-1 tabular-nums text-xl font-bold text-zinc-900 dark:text-white"
                                            >
                                                Rp0
                                            </p>

                                            <p class="mt-2 text-xs text-zinc-500 dark:text-zinc-400">
                                                Berat layak × harga per kg.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-zinc-300 px-6 py-14 text-center dark:border-zinc-700">
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">
                                    Belum ada detail barang
                                </h3>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- GRID INFORMASI PEMBAYARAN + TOTAL --}}
                <div class="grid gap-5 lg:grid-cols-[1fr_360px]">

                    {{-- KOLOM KIRI: Kasbon & Metode Pembayaran --}}
                    <div class="space-y-5">
                        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                Kasbon / Hutang Pelanggan
                            </h2>

                            @if ($hutangAktif)
                                <div class="mt-5 space-y-4">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Kode Kasbon</p>
                                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                                {{ $hutangAktif->kode_hutang }}
                                            </p>
                                        </div>

                                        <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-900/20">
                                            <p class="text-sm text-amber-700 dark:text-amber-300">Sisa Kasbon</p>
                                            <p class="mt-1 tabular-nums font-semibold text-amber-900 dark:text-amber-100">
                                                Rp{{ number_format($hutangAktif->sisa_hutang, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label for="potongan_kasbon" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                            Potongan Kasbon
                                        </label>

                                        <div class="relative">
                                            <input
                                                id="potongan_kasbon"
                                                type="number"
                                                name="potongan_kasbon"
                                                value="{{ old('potongan_kasbon', 0) }}"
                                                min="0"
                                                step="1"
                                                data-potongan-kasbon
                                                data-sisa-hutang="{{ $hutangAktif->sisa_hutang }}"
                                                {{ !$siapBayar ? 'disabled' : '' }}
                                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 pl-12 text-sm text-zinc-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500/20 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:disabled:bg-zinc-800"
                                            >

                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-zinc-500">
                                                Rp
                                            </span>
                                        </div>

                                        <p class="text-xs leading-5 text-zinc-500 dark:text-zinc-400">
                                            Potongan kasbon tidak boleh lebih besar dari total transaksi dan tidak boleh lebih besar dari sisa kasbon.
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-amber-50 p-4 text-sm text-amber-900 dark:bg-amber-900/20 dark:text-amber-200">
                                        Kasbon hanya mengurangi uang yang diterima pelanggan. Berat barang dan hasil fuzzy tidak berubah.
                                    </div>
                                </div>
                            @else
                                <div class="mt-5 rounded-xl bg-zinc-50 p-4 text-sm text-zinc-600 dark:bg-zinc-950 dark:text-zinc-400">
                                    Pelanggan ini tidak memiliki kasbon aktif.
                                </div>

                                <input type="hidden" name="potongan_kasbon" value="0">
                            @endif
                        </div>

                        <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                            <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                                Informasi Pembayaran
                            </h2>

                            <div class="mt-5 grid gap-5 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="metode_pembayaran" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                        Metode Pembayaran
                                    </label>

                                    <select
                                        id="metode_pembayaran"
                                        name="metode_pembayaran"
                                        {{ !$siapBayar ? 'disabled' : '' }}
                                        required
                                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500/20 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:disabled:bg-zinc-800"
                                    >
                                        <option value="tunai" @selected(old('metode_pembayaran') === 'tunai')>Tunai</option>
                                        <option value="transfer" @selected(old('metode_pembayaran') === 'transfer')>Transfer</option>
                                    </select>
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label for="catatan" class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                        Catatan
                                    </label>

                                    <textarea
                                        id="catatan"
                                        name="catatan"
                                        rows="3"
                                        {{ !$siapBayar ? 'disabled' : '' }}
                                        placeholder="Opsional"
                                        class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500/20 disabled:cursor-not-allowed disabled:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:disabled:bg-zinc-800"
                                    >{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: Total Pembayaran --}}
                    <div class="rounded-2xl border border-teal-200 bg-white p-6 shadow-sm dark:border-teal-800 dark:bg-zinc-900">
                        <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">
                            Total Pembayaran
                        </h2>

                        <div class="mt-5 space-y-4">
                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Total Transaksi Barang
                                </p>

                                <p id="grand-total" class="mt-2 tabular-nums text-2xl font-bold text-zinc-900 dark:text-white">
                                    Rp0
                                </p>
                            </div>

                            <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-900/20">
                                <p class="text-sm text-amber-700 dark:text-amber-300">
                                    Potongan Kasbon
                                </p>

                                <p id="potongan-kasbon-display" class="mt-2 tabular-nums text-2xl font-bold text-amber-900 dark:text-amber-100">
                                    Rp0
                                </p>
                            </div>

                            <div class="rounded-xl bg-teal-50 p-5 dark:bg-teal-900/20">
                                <p class="text-sm font-medium text-teal-700 dark:text-teal-300">
                                    Dibayar ke Pelanggan
                                </p>

                                <p id="total-dibayar-pelanggan" class="mt-2 tabular-nums text-3xl font-bold text-teal-950 dark:text-teal-100">
                                    Rp0
                                </p>
                            </div>

                            <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Sisa Kasbon Setelah Bayar
                                </p>

                                <p id="sisa-hutang-setelah" class="mt-2 tabular-nums text-xl font-bold text-zinc-900 dark:text-white">
                                    Rp0
                                </p>
                            </div>

                            <button
                                type="submit"
                                {{ !$siapBayar ? 'disabled' : '' }}
                                class="inline-flex w-full items-center justify-center rounded-xl bg-teal-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 disabled:cursor-not-allowed disabled:bg-zinc-400 dark:bg-teal-600 dark:text-white dark:hover:bg-teal-700 dark:disabled:bg-zinc-700 dark:disabled:text-zinc-400"
                            >
                                Simpan Pembayaran
                            </button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
    const hargaInputs = document.querySelectorAll('[data-harga-input]');
    const grandTotalElement = document.getElementById('grand-total');
    const potonganKasbonInput = document.querySelector('[data-potongan-kasbon]');
    const potonganKasbonDisplay = document.getElementById('potongan-kasbon-display');
    const totalDibayarPelangganElement = document.getElementById('total-dibayar-pelanggan');
    const sisaHutangSetelahElement = document.getElementById('sisa-hutang-setelah');

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value || 0);
    }

    function updateTotalPembayaran() {
        let grandTotal = 0;

        hargaInputs.forEach((input) => {
            const beratLayak = parseFloat(input.dataset.beratLayak || 0);
            const harga = parseFloat(input.value || 0);
            const subtotal = beratLayak * harga;

            grandTotal += subtotal;

            const target = document.getElementById(input.dataset.subtotalTarget);

            if (target) {
                target.textContent = formatRupiah(subtotal);
            }
        });

        const potonganKasbon = potonganKasbonInput
            ? parseFloat(potonganKasbonInput.value || 0)
            : 0;

        const sisaHutang = potonganKasbonInput
            ? parseFloat(potonganKasbonInput.dataset.sisaHutang || 0)
            : 0;

        const totalDibayar = Math.max(grandTotal - potonganKasbon, 0);
        const sisaHutangSetelah = Math.max(sisaHutang - potonganKasbon, 0);

        if (grandTotalElement) {
            grandTotalElement.textContent = formatRupiah(grandTotal);
        }

        if (potonganKasbonDisplay) {
            potonganKasbonDisplay.textContent = formatRupiah(potonganKasbon);
        }

        if (totalDibayarPelangganElement) {
            totalDibayarPelangganElement.textContent = formatRupiah(totalDibayar);
        }

        if (sisaHutangSetelahElement) {
            sisaHutangSetelahElement.textContent = formatRupiah(sisaHutangSetelah);
        }
    }

    hargaInputs.forEach((input) => {
        input.addEventListener('input', updateTotalPembayaran);
    });

    if (potonganKasbonInput) {
        potonganKasbonInput.addEventListener('input', updateTotalPembayaran);
    }

    updateTotalPembayaran();
    </script>
</x-layouts::app>