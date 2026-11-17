<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kontribusi LUWEH</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 18mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #354a17;
        }

        .certificate {
            border: 7px solid #526f12;
            padding: 35px;
            text-align: center;
        }

        .brand {
            font-size: 30px;
            font-weight: bold;
            color: #526f12;
        }

        .title {
            font-size: 25px;
            font-weight: bold;
            margin-top: 22px;
        }

        .description {
            font-size: 14px;
            margin-top: 18px;
        }

        .recipient {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
        }

        .stats {
            background: #f0f4e5;
            padding: 16px;
            margin-top: 25px;
            font-size: 15px;
        }

        .footer {
            margin-top: 35px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="certificate">

        <div class="brand">LUWEH</div>

        <div class="title">
            SERTIFIKAT KONTRIBUSI
        </div>

        <p class="description">
            Sertifikat ini diberikan kepada
        </p>

        <div class="recipient">
            {{ $namaPenerima }}
        </div>

        <p class="description">
            Sebagai apresiasi atas kontribusinya dalam
            upaya mengurangi pemborosan makanan melalui LUWEH.
        </p>

        <div class="stats">
            <strong>Food Saved:</strong>
            {{ $totalFoodSaved === null
                ? 'Belum tersedia'
                : number_format($totalFoodSaved, 2, ',', '.') . ' kg' }}

            &nbsp; | &nbsp;

            <strong>Total Points:</strong>
            {{ number_format($totalPoin, 0, ',', '.') }}
        </div>

        <p class="footer">
            Diterbitkan pada {{ $tanggalTerbit }}
        </p>

    </div>
</body>
</html>
