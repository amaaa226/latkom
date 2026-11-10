@extends('layout')

@section('content')
<h4 class="mb-4">Riwayat Reservasi Saya</h4>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-primary">
                <tr>
                    <th>Kode Booking</th>
                    <th>No Kamar</th>
                    <th>Tipe</th>
                    <th>Tgl Masuk</th>
                    <th>Tgl Keluar</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                <tr>
                    <td><strong>{{ $res->kode_booking }}</strong></td>
                    <td>{{ $res->room->nomor_kamar ?? '-' }}</td>
                    <td class="text-uppercase">{{ $res->room->tipe_kamar ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->tanggal_masuk)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($res->tanggal_keluar)->format('d-m-Y') }}</td>
                    <td>Rp {{ number_format($res->total_biaya, 0, ',', '.') }}</td>
                    <td>
                        @if($res->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($res->status == 'dikonfirmasi')
                            <span class="badge bg-success">Dikonfirmasi</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($res->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('reservations.confirmation', $res->id) }}" class="btn btn-info btn-sm text-white">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
