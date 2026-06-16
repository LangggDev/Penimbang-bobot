<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Pembayaran</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 0;
            padding: 24px;
            font-size: 13px;
        }

        .container {
            width: 100%;
            max-width: 760px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 12px;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 6px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 24px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
        }

        .label {
            color: #555;
        }

        .value {
            font-weight: bold;
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #f3f3f3;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-left: auto;
            width: 320px;
            margin-top: 18px;
        }

        .summary .row {
            border-bottom: 1px solid #ddd;
            padding: 7px 0;
        }

        .summary .grand-total {
            font-size: 16px;
            font-weight: bold;
        }

        .footer {
            margin-top: 32px;
            display: flex;
            justify-content: space-between;
            gap: 32px;
        }

        .sign {
            width: 220px;
            text-align: center;
        }

        .sign-space {
            height: 60px;
        }

        .actions {
            text-align: center;
            margin-top: 24px;
        }

        .num {
            font-variant-numeric: tabular-nums;
            font-weight: 600;
        }

        .grand-total .num {
            font-size: 18px;
        }

        button {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            background: #0F766E;
            color: white;
            cursor: pointer;
            font-weight: 600;
        }

        @media print {
            body {
                padding: 0;
            }

            .actions {
                display: none;
            }

            @page {
                size: A4;
                margin: 14mm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>BUKTI PEMBAYARAN</h1>
            <p>Sistem Bobot Ketidaklayakan Kertas Bekas</p>
        </div>

        <div class="section">
            <div class="section-title">Data Pembayaran</div>

            <div class="grid">
                <div class="row">
                    <span class="label">Kode Pembayaran</span>
                    <span class="value">{{ $pembayaran->kode_pembayaran }}</span>
                </div>

                <div class="row">
                    <span class="label">Kode Transaksi</span>
                    <span class="value">{{ $pembayaran->kode_transaksi }}</span>
                </div>

                <div class="row">
                    <span class="label">Tanggal Bayar</span>
                    <span class="value">
                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d/m/Y H:i') }}
                    </span>
                </div>

                <div class="row">
                    <span class="label">Metode</span>
                    <span class="value">{{ ucfirst($pembayaran->metode_pembayaran) }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Data Pelanggan</div>

            <div class="grid">
                <div class="row">
                    <span class="label">Nama Pelanggan</span>
                    <span class="value">{{ $pembayaran->nama_pelanggan }}</span>
                </div>

                <div class="row">
                    <span class="label">Nomor HP</span>
                    <span class="value">{{ $pembayaran->no_hp ?? '-' }}</span>
                </div>

                <div class="row">
                    <span class="label">Kendaraan</span>
                    <span class="value">{{ $pembayaran->nama_kendaraan }}</span>
                </div>

                <div class="row">
                    <span class="label">Plat Kendaraan</span>
                    <span class="value">{{ $pembayaran->plat_kendaraan ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Rincian Barang</div>

            <table>
                <thead>
                    <tr>
                        <th>Jenis Kertas</th>
                        <th class="text-right">Berat Bersih</th>
                        <th class="text-right">Potongan</th>
                        <th class="text-right">Berat Layak</th>
                        <th class="text-right">Harga / Kg</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detailBarang as $item)
                        <tr>
                            <td>{{ $item->nama_barang }}</td>
                            <td class="text-right num">{{ number_format($item->berat_bersih, 2, ',', '.') }} kg</td>
                            <td class="text-right num">
                                {{ number_format($item->persentase_potongan, 2, ',', '.') }}%
                                <br>
                                {{ number_format($item->potongan_berat, 2, ',', '.') }} kg
                            </td>
                            <td class="text-right num">{{ number_format($item->berat_layak, 2, ',', '.') }} kg</td>
                            <td class="text-right num">Rp{{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                            <td class="text-right num">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center;">Tidak ada detail barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="summary">
                <div class="row">
                    <span>Total Berat Bersih</span>
                    <strong class="num">{{ number_format($pembayaran->total_berat_bersih, 2, ',', '.') }} kg</strong>
                </div>

                <div class="row">
                    <span>Total Berat Layak</span>
                    <strong class="num">{{ number_format($pembayaran->total_berat_layak, 2, ',', '.') }} kg</strong>
                </div>

                <div class="row">
                    <span>Total Transaksi</span>
                    <strong class="num">Rp{{ number_format($pembayaran->total_transaksi, 0, ',', '.') }}</strong>
                </div>

                <div class="row">
                    <span>Potongan Kasbon</span>
                    <strong class="num">Rp{{ number_format($pembayaran->potongan_kasbon, 0, ',', '.') }}</strong>
                </div>

                <div class="row grand-total">
                    <span>Dibayar</span>
                    <strong class="num">Rp{{ number_format($pembayaran->total_dibayar_ke_pelanggan, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="sign">
                <p>Kasir</p>
                <div class="sign-space"></div>
                <p>(________________)</p>
            </div>

            <div class="sign">
                <p>Pelanggan</p>
                <div class="sign-space"></div>
                <p>(________________)</p>
            </div>
        </div>

        <div class="actions">
            <button onclick="window.print()">Print</button>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>