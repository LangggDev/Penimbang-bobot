<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Nomor Antrian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            padding: 24px;
        }

        .ticket {
            width: 360px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
        }

        .queue {
            font-size: 64px;
            font-weight: bold;
            margin: 20px 0;
        }

        .info {
            text-align: left;
            font-size: 14px;
            line-height: 1.8;
            margin-top: 20px;
        }

        .button {
            margin-top: 20px;
        }

        @media print {
            .button {
                display: none;
            }

            body {
                padding: 0;
            }

            .ticket {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="title">Nomor Antrian</div>

        <div class="queue">
            {{ $nomorAntrian }}
        </div>

        <div class="info">
            <div><strong>Kode Transaksi:</strong> {{ $transaksi->kode_transaksi }}</div>
            <div><strong>Pelanggan:</strong> {{ $transaksi->nama_pelanggan }}</div>
            <div><strong>Kendaraan:</strong> {{ $transaksi->nama_kendaraan }}</div>
            <div><strong>Plat:</strong> {{ $transaksi->plat_kendaraan ?? '-' }}</div>
            <div><strong>Total Bersih:</strong> {{ number_format($totalBeratBersih, 2, ',', '.') }} kg</div>
            <div><strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $transaksi->status)) }}</div>
            <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}</div>
        </div>

        <div class="button">
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