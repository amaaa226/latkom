<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Reservasi</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5" style="max-width: 550px;">
        <div class="card p-4 shadow-sm text-center">

            <div class="text-success mb-2" style="font-size: 48px;">✓</div>
            <h3 class="fw-bold mb-1">Reservasi Berhasil!</h3>
            <p class="text-muted small mb-4">Terima kasih, pemesanan Anda telah kami terima.</p>

            <div class="bg-light p-3 rounded border text-start mb-4">
                <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                    <span class="text-secondary">Kode Booking</span>
                    <strong class="text-primary font-monospace">{{ $reservation->kode_booking }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Nama Pemesan</span>
                    <span>{{ $reservation->customer->nama }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Kamar</span>
                    <span>Kamar {{ $reservation->room->nomor_kamar }} ({{ ucfirst($reservation->room->tipe_kamar) }})</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Check-In</span>
                    <span>{{ \Carbon\Carbon::parse($reservation->tanggal_masuk)->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Check-Out</span>
                    <span>{{ \Carbon\Carbon::parse($reservation->tanggal_keluar)->format('d/m/Y') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Durasi</span>
                    <span>{{ $reservation->durasi_menginap }} Malam</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total Biaya</span>
                    <span class="fs-5 fw-bold text-success">Rp {{ number_format($reservation->total_biaya, 0, ',', '.') }}</span>
                </div>
            </div>

            <a href="{{ route('rooms.index') }}" class="btn btn-primary w-100">Kembali ke Beranda</a>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
