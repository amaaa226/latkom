<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Konfirmasi Reservasi</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Admin Hotel</a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.index') }}" class="btn btn-outline-light btn-sm">Kelola Kamar</a>
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-light btn-sm active">Konfirmasi Reservasi</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <h4 class="mb-3">Konfirmasi Pembayaran Customer</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode Booking</th>
                            <th>Pelanggan</th>
                            <th>No Kamar</th>
                            <th>Total Biaya</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                        <tr>
                            <td><strong>{{ $res->kode_booking }}</strong></td>
                            <td>
                                <div>{{ $res->customer->nama ?? '-' }}</div>
                                <small class="text-muted">{{ $res->customer->email ?? '' }}</small>
                            </td>
                            <td>Kamar {{ $res->room->nomor_kamar ?? '-' }}</td>
                            <td>Rp {{ number_format($res->total_biaya, 0, ',', '.') }}</td>
                            <td>
                                @if($res->status == 'pending')
                                    <span class="badge bg-warning text-dark">Belum Bayar</span>
                                @elseif($res->status == 'dikonfirmasi')
                                    <span class="badge bg-success">Sudah Bayar</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($res->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($res->status == 'pending')
                                    <form action="{{ route('admin.reservations.confirm', $res->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm">Tandai Sudah Bayar</button>
                                    </form>
                                @else
                                    <span class="text-muted small">Terkonfirmasi</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">Belum ada data reservasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
