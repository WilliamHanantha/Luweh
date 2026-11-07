<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Home - LUWEH</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6ef; color: #3a4a1a; }
        header { background: #556b16; color: #fff; padding: 16px 24px; font-weight: bold; font-size: 20px; }
        main { max-width: 480px; margin: 0 auto; padding: 16px; }
        .sapaan { background: #8a9f2e; color: #fff; border-radius: 12px; padding: 16px; margin-bottom: 16px; }
        .resto { display: block; background: #fff; border-radius: 12px; padding: 12px 16px; margin-bottom: 12px; text-decoration: none; color: inherit; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        .resto h3 { margin: 0 0 4px; }
        .info { font-size: 14px; color: #6b7a3a; }
    </style>
</head>
<body>
    <header>LUWEH</header>

    <main>
        <div class="sapaan">
            <div>Hello!</div>
            <strong>Save the Food, now!</strong>
        </div>

        <h2>Recommendation</h2>

        @forelse ($restorans as $resto)
            <a href="#" class="resto">
                <h3>{{ $resto->nama_resto }}</h3>
                <div class="info">
                    {{ number_format($resto->jarak, 1) }} km &middot; ★ {{ number_format($resto->rating, 1) }}
                </div>
                <div class="info">{{ $resto->alamat }}</div>
            </a>
        @empty
            <p>Belum ada restoran.</p>
        @endforelse
    </main>
</body>
</html>
