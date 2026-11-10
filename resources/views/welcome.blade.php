@extends('layout')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card p-3 shadow-sm">
                <form action="{{ route('rooms.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control"
                            value="{{ request('tanggal_masuk') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" class="form-control"
                            value="{{ request('tanggal_keluar') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipe Kamar</label>
                        <select name="tipe_kamar" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="standard" {{ request('tipe_kamar') == 'standard' ? 'selected' : '' }}>Standard
                            </option>
                            <option value="superior" {{ request('tipe_kamar') == 'superior' ? 'selected' : '' }}>Superior
                            </option>
                            <option value="deluxe" {{ request('tipe_kamar') == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h4 class="mb-3">Daftar Kamar</h4>

    <div class="row">
        @forelse($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if (Str::startsWith($room->foto, ['http://', 'https://']))
                        <img src="{{ $room->foto }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @elseif(Str::startsWith($room->foto, 'images/'))
                        <img src="{{ asset($room->foto) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/' . $room->foto) }}" class="card-img-top"
                            style="height: 200px; object-fit: cover;">
                    @endif

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0">Kamar {{ $room->nomor_kamar }}</h5>
                            @if ($room->status == 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @elseif($room->status == 'dipesan')
                                <span class="badge bg-danger">Dipesan</span>
                            @else
                                <span class="badge bg-warning text-dark">Perbaikan</span>
                            @endif
                        </div>
                        <span class="badge bg-info text-dark mb-2 text-uppercase">{{ $room->tipe_kamar }}</span>
                        <p class="card-text text-muted small">{{ $room->deskripsi }}</p>
                        <h6 class="text-success fw-bold">Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }} /
                            malam</h6>
                    </div>

                    <div class="card-footer bg-white border-0 pb-3">
                        @if ($room->status == 'tersedia')
                            <a href="{{ route('reservations.create', $room->id) }}?tanggal_masuk={{ request('tanggal_masuk') }}&tanggal_keluar={{ request('tanggal_keluar') }}"
                                class="btn btn-outline-primary w-100">Pesan Sekarang</a>
                        @else
                            <button class="btn btn-secondary w-100" disabled>Tidak Tersedia</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Tidak ada kamar yang sesuai dengan kriteria pencarian.</p>
            </div>
        @endforelse
    </div>
@endsection
