@extends('layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <h3 class="fw-bold mb-4">Form Transaksi Reservasi</h3>

        <div class="row g-4">
            <!-- Form Data Pemesan -->
            <div class="col-md-7">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-3">Data Pemesan</h5>
                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <input type="hidden" name="tanggal_masuk" value="{{ $checkIn->format('Y-m-d') }}">
                        <input type="hidden" name="tanggal_keluar" value="{{ $checkOut->format('Y-m-d') }}">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ old('nama', Auth::user()->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="nomor_telepon" class="form-control" placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="d-flex gap-2 pt-2">
                            <a href="{{ route('rooms.index') }}" class="btn btn-secondary w-50">Kembali</a>
                            <button type="submit" class="btn btn-primary w-50">Konfirmasi Pemesanan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Rincian Menginap -->
            <div class="col-md-5">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="fw-bold mb-3">Rincian Menginap</h5>

                    <p class="mb-1"><strong>Nomor Kamar:</strong> {{ $room->nomor_kamar }}</p>
                    <p class="mb-3"><strong>Tipe Kamar:</strong> {{ ucfirst($room->tipe_kamar) }}</p>

                    <hr>

                    <p class="mb-1"><strong>Check-In:</strong> {{ $checkIn->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Check-Out:</strong> {{ $checkOut->format('d/m/Y') }}</p>
                    <p class="mb-1"><strong>Durasi:</strong> {{ $durationNights }} Malam</p>
                    <p class="mb-3"><strong>Harga/Malam:</strong> Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}</p>

                    <hr>

                    <h5 class="text-success fw-bold">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
