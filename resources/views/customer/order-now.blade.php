<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Now - LUWEH</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6ef; color: #3a4a1a; }
        header { background: #556b16; color: #fff; padding: 16px 24px; font-weight: bold; font-size: 20px; }
        main { max-width: 480px; margin: 0 auto; padding: 16px; }
        .item { display: flex; justify-content: space-between; align-items: center; background: #fff; border-radius: 12px; padding: 12px 16px; margin-bottom: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.1); }
        .item h3 { margin: 0 0 4px; }
        .harga { font-size: 14px; color: #6b7a3a; }
        .qty { display: flex; align-items: center; gap: 8px; }
        .qty form { margin: 0; }
        .qty button { width: 32px; height: 32px; border-radius: 50%; border: 1px solid #556b16; background: #fff; color: #556b16; font-size: 18px; cursor: pointer; }
        .total { text-align: right; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
    <header>LUWEH</header>

    <main>
        <h2>Order Now</h2>

        @if ($pesanan && $pesanan->itemPesanans->count() > 0)
            @if ($pesanan->restoran)
                <p><strong>{{ $pesanan->restoran->nama_resto }}</strong></p>
            @endif

            @foreach ($pesanan->itemPesanans as $item)
                <div class="item">
                    <div>
                        <h3>{{ $item->daftarMakanan->nama_makanan }}</h3>
                        <div class="harga">Rp {{ number_format($item->daftarMakanan->harga, 0, ',', '.') }}</div>
                    </div>

                    <div class="qty">
                        <form action="/order-now/item/{{ $item->id }}/kurang" method="POST">
                            @csrf
                            <button type="submit">-</button>
                        </form>

                        <span>{{ $item->kuantitas }}</span>

                        <form action="/order-now/item/{{ $item->id }}/tambah" method="POST">
                            @csrf
                            <button type="submit">+</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="total">
                Total: Rp {{ number_format($pesanan->itemPesanans->sum(fn ($i) => $i->daftarMakanan->harga * $i->kuantitas), 0, ',', '.') }}
            </div>
        @else
            <p>Keranjang masih kosong.</p>
            <a href="/customer-home">Kembali ke Customer Home</a>
        @endif
    </main>
</body>
</html>
