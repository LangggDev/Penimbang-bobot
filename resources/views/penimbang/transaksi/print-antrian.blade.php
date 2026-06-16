<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Nomor Antrian - {{ $transaksi->kode_transaksi }}</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .ticket {
            width: 380px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
        }

        .header {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }

        .queue {
            font-size: 72px;
            font-weight: 800;
            color: #0f766e;
            margin: 24px 0;
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .divider {
            border-top: 1px dashed #cbd5e1;
            margin: 24px 0;
        }

        .info {
            text-align: left;
            font-size: 14px;
            color: #334155;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }

        .info-value-mono {
            font-family: monospace;
            font-size: 15px;
        }

        .button-container {
            margin-top: 32px;
        }

        .btn-print {
            background-color: #0f766e;
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        }

        .btn-print:hover {
            background-color: #115e59;
        }

        @media print {
            body {
                padding: 0;
                background-color: #ffffff;
            }

            .ticket {
                border: none;
                box-shadow: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }

            .button-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">Nomor Antrian</div>
        <div class="title">Sistem Kertas Bekas</div>

        <div class="queue">
            {{ $nomorAntrian }}
        </div>

        <div class="divider"></div>

        <div class="info">
            <div class="info-row">
                <span class="info-label">Kode Transaksi</span>
                <span class="info-value info-value-mono">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pelanggan</span>
                <span class="info-value">{{ $transaksi->nama_pelanggan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Kendaraan</span>
                <span class="info-value">{{ $transaksi->nama_kendaraan }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Plat Nomor</span>
                <span class="info-value info-value-mono">{{ $transaksi->plat_kendaraan ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Bersih</span>
                <span class="info-value">{{ number_format($totalBeratBersih, 2, ',', '.') }} kg</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value">{{ ucwords(str_replace('_', ' ', $transaksi->status)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->translatedFormat('d/m/Y H:i') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="button-container">
            <button class="btn-print" onclick="window.print()">Print Tiket</button>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>