<x-layouts::app :title="'Detail Perhitungan Fuzzy'">
    @php
        $input = $perhitungan['input'] ?? [];

        $getInputValue = function ($key, $fallback = '-') use ($input) {
            $value = data_get($input, $key);

            if (is_array($value)) {
                return $value['nilai'] ?? $fallback;
            }

            return $value ?? $fallback;
        };

        $formatNumber = function ($value, $decimal = 2) {
            if ($value === null || $value === '-' || $value === '') {
                return '-';
            }

            return number_format((float) $value, $decimal, ',', '.');
        };

        $fuzzifikasi = data_get($perhitungan, 'fuzzifikasi', []);

        $rules = data_get($perhitungan, 'inferensi.rules', data_get($perhitungan, 'rules', []));

        $totalAlpha = data_get($perhitungan, 'defuzzifikasi.total_alpha', data_get($perhitungan, 'total_alpha', '-'));
        $totalAlphaZ = data_get($perhitungan, 'defuzzifikasi.total_alpha_z', data_get($perhitungan, 'total_alpha_z', '-'));
        $defuzzifikasiRumus = data_get($perhitungan, 'defuzzifikasi.rumus', 'Z = Σ(αi × zi) / Σαi');
        $defuzzifikasiPerhitungan = data_get($perhitungan, 'defuzzifikasi.perhitungan', '-');

        $hasilAkhir = data_get($perhitungan, 'hasil_akhir', []);
    @endphp

    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wide text-teal-600 dark:text-teal-400">
                        Menu QC
                    </p>

                    <h1 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Detail Perhitungan Fuzzy Tsukamoto
                    </h1>

                    <p class="max-w-3xl text-sm leading-6 text-slate-600 dark:text-zinc-400">
                        Menampilkan tahapan fuzzifikasi, inferensi, defuzzifikasi, dan hasil akhir perhitungan bobot ketidaklayakan.
                    </p>
                </div>

                <a
                    href="{{ route('qc.fuzzy.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800"
                >
                    Kembali
                </a>
            </div>

            {{-- Catatan --}}
            <div class="rounded-2xl border border-teal-100 bg-teal-50 p-5 dark:border-teal-900/30 dark:bg-teal-900/10">
                <h2 class="text-sm font-semibold text-teal-800 dark:text-teal-300">
                    Catatan Perhitungan
                </h2>

                <p class="mt-2 text-sm leading-6 text-teal-700 dark:text-teal-400">
                    Perhitungan Fuzzy Tsukamoto dilakukan dengan mengubah nilai input menjadi derajat keanggotaan,
                    mengevaluasi rule aktif menggunakan nilai α-predikat, mencari nilai z setiap rule,
                    lalu melakukan defuzzifikasi untuk mendapatkan bobot ketidaklayakan.
                </p>
            </div>

            {{-- Data transaksi dan hasil akhir ringkas --}}
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-t-4 border-t-slate-400">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Data Transaksi
                    </h2>

                    <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Kode Transaksi</p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                                {{ $hasil->kode_transaksi }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Pelanggan</p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                                {{ $hasil->nama_pelanggan }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Jenis Kertas</p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                                {{ $hasil->nama_barang }}
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Kendaraan</p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                                {{ $hasil->nama_kendaraan }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-t-4 border-t-teal-600">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        Hasil Akhir Ringkas
                    </h2>

                    <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2">
                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Berat Bersih</p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                {{ number_format($hasil->total_berat_bersih, 2, ',', '.') }} kg
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Bobot Ketidaklayakan</p>
                            <p class="mt-1 font-bold text-teal-600 dark:text-teal-400 tabular-nums">
                                {{ number_format($hasil->nilai_bobot_ketidaklayakan, 2, ',', '.') }}%
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Potongan Berat</p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                {{ number_format($hasil->potongan_berat, 2, ',', '.') }} kg
                            </p>
                        </div>

                        <div>
                            <p class="text-slate-500 dark:text-zinc-400">Berat Layak</p>
                            <p class="mt-1 font-bold text-green-600 dark:text-green-400 tabular-nums">
                                {{ number_format($hasil->berat_layak, 2, ',', '.') }} kg
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input variabel --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    1. Input Variabel
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                    Nilai crisp yang menjadi input awal perhitungan fuzzy.
                </p>

                <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                        <p class="text-slate-500 dark:text-zinc-400">Jenis Kendaraan</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                            {{ $getInputValue('jenis_kendaraan', $hasil->nilai_jenis_kendaraan) }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                        <p class="text-slate-500 dark:text-zinc-400">Berat Kotor</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format((float) $getInputValue('berat_kotor', $hasil->nilai_berat_kotor), 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                        <p class="text-slate-500 dark:text-zinc-400">Berat Bersih</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format((float) $getInputValue('berat_bersih', $hasil->nilai_berat_bersih), 2, ',', '.') }} kg
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                        <p class="text-slate-500 dark:text-zinc-400">Kualitas Kertas</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format((float) $getInputValue('kualitas_kertas', $hasil->nilai_kualitas_kertas), 2, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Fuzzifikasi --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        2. Fuzzifikasi
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Mengubah nilai crisp menjadi derajat keanggotaan μ pada setiap himpunan fuzzy.
                    </p>
                </div>

                <div class="space-y-6">
                    @forelse ($fuzzifikasi as $kodeVariabel => $variabel)
                        @php
                            $labelVariabel = data_get($variabel, 'label', ucwords(str_replace('_', ' ', $kodeVariabel)));
                            $nilaiCrisp = data_get($variabel, 'nilai_crisp', data_get($input, $kodeVariabel . '.nilai', '-'));
                            $satuan = data_get($variabel, 'satuan');
                            $himpunanItems = data_get($variabel, 'himpunan', []);
                        @endphp

                        <div class="rounded-2xl border border-slate-200 p-5 dark:border-zinc-800 bg-white shadow-sm">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between bg-white dark:bg-zinc-900">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                        {{ $labelVariabel }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                                        Nilai crisp:
                                        <span class="font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ is_numeric($nilaiCrisp) ? number_format((float) $nilaiCrisp, 2, ',', '.') : $nilaiCrisp }}
                                            {{ $satuan }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-3">
                                @foreach ($himpunanItems as $item)
                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm text-slate-500 dark:text-zinc-400">Himpunan</p>
                                                    <p class="mt-1 font-semibold text-slate-900 dark:text-white">
                                                        {{ data_get($item, 'nama_himpunan', data_get($item, 'kode_himpunan', '-')) }}
                                                    </p>
                                                </div>

                                                <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 dark:bg-zinc-850 dark:text-zinc-300">
                                                    {{ data_get($item, 'tipe_fungsi', '-') }}
                                                </span>
                                            </div>

                                            <div class="mt-4 grid gap-2 text-xs text-slate-500 dark:text-zinc-400">
                                                <p>Domain: <span class="tabular-nums font-medium">{{ data_get($item, 'domain_min', '-') }} - {{ data_get($item, 'domain_max', '-') }}</span></p>
                                                <p>a: <span class="tabular-nums">{{ data_get($item, 'nilai_a', '-') }}</span>, b: <span class="tabular-nums">{{ data_get($item, 'nilai_b', '-') }}</span>, c: <span class="tabular-nums">{{ data_get($item, 'nilai_c', '-') }}</span>, d: <span class="tabular-nums">{{ data_get($item, 'nilai_d', '-') }}</span></p>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="mt-4 rounded-lg bg-white p-3 dark:bg-zinc-900 border border-slate-100 dark:border-zinc-800 shadow-sm">
                                                <p class="text-xs text-slate-500 dark:text-zinc-400">Nilai μ</p>
                                                <p class="mt-1 text-2xl font-bold text-teal-600 dark:text-teal-400 tabular-nums">
                                                    {{ number_format((float) data_get($item, 'nilai_mu', 0), 4, ',', '.') }}
                                                </p>
                                            </div>

                                            <p class="mt-3 text-xs leading-5 text-slate-600 dark:text-zinc-400 border-t border-slate-100 dark:border-zinc-900 pt-2">
                                                {{ data_get($item, 'rumus', '-') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                Data fuzzifikasi belum tersedia
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Simpan ulang penilaian QC agar fuzzy dihitung ulang dengan format detail terbaru.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Inferensi --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                        3. Inferensi / Evaluasi Rule
                    </h2>

                    <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                        Setiap rule dievaluasi. Nilai α-predikat diperoleh dari nilai minimum derajat keanggotaan setiap variabel. Active rules (α > 0) diberi sorotan warna teal.
                    </p>
                </div>

                <div class="space-y-6">
                    @forelse ($rules as $rule)
                        @php
                            $membership = data_get($rule, 'membership', data_get($rule, 'mu', []));
                            $ruleText = data_get($rule, 'rule_text', data_get($rule, 'rule', '-'));

                            $alphaRaw = $rule['alpha'] ?? 0;
                            $alphaValue = data_get($rule, 'alpha.nilai', is_array($alphaRaw) ? 0 : $alphaRaw);
                            $alphaText = data_get($rule, 'alpha.perhitungan', 'α = min(...) = ' . $alphaValue);

                            $zRaw = $rule['z'] ?? 0;
                            $zValue = data_get($rule, 'z.nilai', is_array($zRaw) ? 0 : $zRaw);
                            $zText = data_get($rule, 'z.rumus', 'z = ' . $zValue);

                            $alphaZRaw = $rule['alpha_z'] ?? 0;
                            $alphaZValue = data_get($rule, 'alpha_z.nilai', is_array($alphaZRaw) ? 0 : $alphaZRaw);
                            $alphaZText = data_get($rule, 'alpha_z.perhitungan', 'αz = ' . $alphaZValue);

                            $output = data_get($rule, 'output', []);

                            $isActiveRule = $alphaValue > 0;
                            $cardClass = $isActiveRule
                                ? 'rounded-2xl border border-teal-200 bg-teal-50/5 p-5 dark:border-teal-900/40 bg-white shadow-sm border-l-4 border-l-teal-500'
                                : 'rounded-2xl border border-slate-200 p-5 dark:border-zinc-800 bg-white shadow-sm';
                        @endphp

                        <div class="{{ $cardClass }}">
                            <div class="space-y-5 bg-white dark:bg-zinc-900">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ data_get($rule, 'kode_rule', '-') }}
                                        </h3>

                                        <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            Output: {{ data_get($output, 'bobot_ketidaklayakan', data_get($rule, 'bobot_ketidaklayakan', '-')) }}
                                        </span>

                                        @if ($isActiveRule)
                                            <span class="rounded-full bg-teal-100 px-3 py-1 text-xs font-bold text-teal-800 dark:bg-teal-900/40 dark:text-teal-300">
                                                Rule Aktif
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-zinc-400 font-medium">
                                        {{ $ruleText }}
                                    </p>
                                </div>

                                <div class="grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-4">
                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                                        <p class="text-slate-500 dark:text-zinc-400">μ Jenis Kendaraan</p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format((float) data_get($membership, 'jenis_kendaraan', 0), 4, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                                        <p class="text-slate-500 dark:text-zinc-400">μ Berat Kotor</p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format((float) data_get($membership, 'berat_kotor', 0), 4, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                                        <p class="text-slate-500 dark:text-zinc-400">μ Berat Bersih</p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format((float) data_get($membership, 'berat_bersih', 0), 4, ',', '.') }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                                        <p class="text-slate-500 dark:text-zinc-400">μ Kualitas Kertas</p>
                                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                                            {{ number_format((float) data_get($membership, 'kualitas_kertas', 0), 4, ',', '.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid gap-3 text-sm lg:grid-cols-3">
                                    <div class="rounded-xl bg-blue-50 p-4 text-blue-900 dark:bg-blue-900/20 dark:text-blue-100 border border-blue-100 dark:border-blue-900/30">
                                        <p class="font-medium text-blue-800 dark:text-blue-300">α-predikat</p>
                                        <p class="mt-1 text-lg font-bold tabular-nums">
                                            {{ number_format((float) $alphaValue, 4, ',', '.') }}
                                        </p>
                                        <p class="mt-2 text-xs leading-5 text-blue-700/80 dark:text-blue-300/80">
                                            {{ $alphaText }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-green-50 p-4 text-green-900 dark:bg-green-900/20 dark:text-green-100 border border-green-100 dark:border-green-900/30">
                                        <p class="font-medium text-green-800 dark:text-green-300">Nilai z</p>
                                        <p class="mt-1 text-lg font-bold tabular-nums">
                                            {{ number_format((float) $zValue, 4, ',', '.') }}
                                        </p>
                                        <p class="mt-2 text-xs leading-5 text-green-700/80 dark:text-green-300/80">
                                            {{ $zText }}
                                        </p>
                                    </div>

                                    <div class="rounded-xl bg-teal-50 p-4 text-teal-900 dark:bg-teal-900/20 dark:text-teal-100 border border-teal-100 dark:border-teal-900/30">
                                        <p class="font-medium text-teal-800 dark:text-teal-300 font-bold">α × z</p>
                                        <p class="mt-1 text-lg font-bold tabular-nums text-teal-700 dark:text-teal-400">
                                            {{ number_format((float) $alphaZValue, 4, ',', '.') }}
                                        </p>
                                        <p class="mt-2 text-xs leading-5 text-teal-700/80 dark:text-teal-400/80">
                                            {{ $alphaZText }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center dark:border-zinc-700">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">
                                Detail rule tidak tersedia
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 dark:text-zinc-400">
                                Data rule belum ditemukan di detail_perhitungan. Simpan ulang QC agar fuzzy dihitung ulang dengan format terbaru.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Defuzzifikasi --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    4. Defuzzifikasi
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                    Mengubah hasil inferensi fuzzy menjadi satu nilai crisp bobot ketidaklayakan.
                </p>

                <div class="mt-5 grid gap-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                        <p class="text-slate-500 dark:text-zinc-400">Total α</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                            {{ is_numeric($totalAlpha) ? number_format((float) $totalAlpha, 4, ',', '.') : '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm">
                        <p class="text-slate-500 dark:text-zinc-400">Total αz</p>
                        <p class="mt-1 font-semibold text-slate-900 dark:text-white tabular-nums">
                            {{ is_numeric($totalAlphaZ) ? number_format((float) $totalAlphaZ, 4, ',', '.') : '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-teal-50 p-4 text-teal-900 dark:bg-teal-900/20 dark:text-teal-100 border border-teal-100 dark:border-teal-900/30">
                        <p class="text-teal-700 dark:text-teal-300 font-medium">Z = Σαz / Σα</p>
                        <p class="mt-1 font-bold text-teal-600 dark:text-teal-400 tabular-nums text-lg">
                            {{ number_format($hasil->nilai_bobot_ketidaklayakan, 2, ',', '.') }}%
                        </p>
                    </div>

                    <div class="rounded-xl bg-green-50 p-4 text-green-900 dark:bg-green-900/20 dark:text-green-100 border border-green-100 dark:border-green-900/30">
                        <p class="text-green-700 dark:text-green-300 font-medium">Berat Layak</p>
                        <p class="mt-1 font-bold text-green-600 dark:text-green-400 tabular-nums text-lg">
                            {{ number_format($hasil->berat_layak, 2, ',', '.') }} kg
                        </p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900 dark:border-blue-900/40 dark:bg-blue-900/20 dark:text-blue-100">
                    <p class="font-semibold">
                        {{ $defuzzifikasiRumus }}
                    </p>

                    <p class="mt-1">
                        {{ $defuzzifikasiPerhitungan }}
                    </p>
                </div>
            </div>

            {{-- Hasil akhir detail --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 border-b-4 border-b-teal-600">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
                    5. Hasil Akhir
                </h2>

                <p class="mt-1 text-sm text-slate-500 dark:text-zinc-400">
                    Hasil akhir digunakan untuk menentukan berat layak yang nanti dipakai pada proses pembayaran.
                </p>

                <div class="mt-5 grid gap-4 text-sm md:grid-cols-3">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm border-l-4 border-l-teal-500">
                        <p class="text-slate-500 dark:text-zinc-400">Bobot Ketidaklayakan</p>
                        <p class="mt-1 text-2xl font-bold text-teal-600 dark:text-teal-400 tabular-nums">
                            {{ number_format($hasil->nilai_bobot_ketidaklayakan, 2, ',', '.') }}%
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm border-l-4 border-l-red-500">
                        <p class="text-slate-500 dark:text-zinc-400">Potongan Berat</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white tabular-nums">
                            {{ number_format($hasil->potongan_berat, 2, ',', '.') }} kg
                        </p>

                        @if (data_get($hasilAkhir, 'potongan_berat.perhitungan'))
                            <p class="mt-2 text-xs leading-5 text-slate-500 dark:text-zinc-400">
                                {{ data_get($hasilAkhir, 'potongan_berat.perhitungan') }}
                            </p>
                        @endif
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-zinc-950 border border-slate-100 dark:border-zinc-900 shadow-sm border-l-4 border-l-green-600">
                        <p class="text-slate-500 dark:text-zinc-400">Berat Layak</p>
                        <p class="mt-1 text-2xl font-bold text-green-600 dark:text-green-400 tabular-nums">
                            {{ number_format($hasil->berat_layak, 2, ',', '.') }} kg
                        </p>

                        @if (data_get($hasilAkhir, 'berat_layak.perhitungan'))
                            <p class="mt-2 text-xs leading-5 text-slate-500 dark:text-zinc-400">
                                {{ data_get($hasilAkhir, 'berat_layak.perhitungan') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layouts::app>