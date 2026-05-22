<x-layouts::app :title="'Edit Kasbon'">
    <div class="px-6 py-6 lg:px-8 lg:py-8">
        <div class="mx-auto max-w-4xl space-y-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Menu Kasir</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">
                        Edit Kasbon Pelanggan
                    </h1>
                </div>

                <a
                    href="{{ route('kasir.kasbon.index') }}"
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

            @if ($sudahAdaPembayaran)
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-900 dark:border-yellow-900/40 dark:bg-yellow-900/20 dark:text-yellow-200">
                    Kasbon ini sudah memiliki riwayat pembayaran. Nominal tidak boleh diubah agar riwayat tetap valid.
                </div>
            @endif

            <form method="POST" action="{{ route('kasir.kasbon.update', $hutang->id) }}" class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Pelanggan</p>
                        <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                            {{ $hutang->nama_pelanggan }} - {{ $hutang->kode_pelanggan }}
                        </p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Tanggal Kasbon
                            </label>

                            <input
                                type="date"
                                name="tanggal_hutang"
                                value="{{ old('tanggal_hutang', \Carbon\Carbon::parse($hutang->tanggal_hutang)->toDateString()) }}"
                                {{ $sudahAdaPembayaran ? 'readonly' : '' }}
                                required
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 read-only:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:read-only:bg-zinc-800"
                            >
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                Nominal Kasbon
                            </label>

                            <input
                                type="number"
                                name="total_hutang"
                                value="{{ old('total_hutang', $hutang->total_hutang) }}"
                                min="1"
                                step="1"
                                {{ $sudahAdaPembayaran ? 'readonly' : '' }}
                                required
                                class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 read-only:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white dark:read-only:bg-zinc-800"
                            >
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Kasbon</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                Rp{{ number_format($hutang->total_hutang, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total Terbayar</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                Rp{{ number_format($hutang->total_terbayar, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-xl bg-zinc-50 p-4 dark:bg-zinc-950">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Sisa Kasbon</p>
                            <p class="mt-1 font-semibold text-zinc-900 dark:text-white">
                                Rp{{ number_format($hutang->sisa_hutang, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            rows="4"
                            placeholder="Opsional"
                            class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm text-zinc-900 shadow-sm focus:border-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 dark:border-zinc-700 dark:bg-zinc-900 dark:text-white"
                        >{{ old('keterangan', $hutang->keterangan) }}</textarea>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-zinc-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-layouts::app>