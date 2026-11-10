<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kelola Kamar</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Admin Hotel</a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.index') }}" class="btn btn-outline-light btn-sm active">Kelola Kamar</a>
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-light btn-sm">Konfirmasi Reservasi</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Manajemen Kamar</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Kamar</button>
        </div>

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
                            <th>Foto</th>
                            <th>No Kamar</th>
                            <th>Tipe</th>
                            <th>Harga / Malam</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                        <tr>
                            <td>
                                @if(Str::startsWith($room->foto, ['http://', 'https://']))
                                    <img src="{{ $room->foto }}" width="60" height="40" class="rounded object-fit-cover">
                                @else
                                    <img src="{{ asset($room->foto) }}" width="60" height="40" class="rounded object-fit-cover">
                                @endif
                            </td>
                            <td><strong>{{ $room->nomor_kamar }}</strong></td>
                            <td class="text-uppercase">{{ $room->tipe_kamar }}</td>
                            <td>Rp {{ number_format($room->harga_per_malam, 0, ',', '.') }}</td>
                            <td><small class="text-muted">{{ $room->deskripsi ?? '-' }}</small></td>
                            <td>
                                @if($room->status == 'tersedia')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($room->status == 'dipesan')
                                    <span class="badge bg-danger">Dipesan</span>
                                @else
                                    <span class="badge bg-warning text-dark">Perbaikan</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $room->id }}">Edit</button>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Hapus kamar ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- MODAL EDIT KAMAR -->
                        <div class="modal fade" id="modalEdit{{ $room->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kamar {{ $room->nomor_kamar }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Foto Kamar (Kosongkan jika tidak diubah)</label>
                                                <input type="file" name="foto" class="form-control" accept="image/*">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor Kamar</label>
                                                <input type="text" name="nomor_kamar" class="form-control" value="{{ $room->nomor_kamar }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Tipe Kamar</label>
                                                <select name="tipe_kamar" class="form-select" required>
                                                    <option value="standard" {{ $room->tipe_kamar == 'standard' ? 'selected' : '' }}>Standard</option>
                                                    <option value="superior" {{ $room->tipe_kamar == 'superior' ? 'selected' : '' }}>Superior</option>
                                                    <option value="deluxe" {{ $room->tipe_kamar == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Harga Per Malam (Rp)</label>
                                                <input type="number" name="harga_per_malam" class="form-control" value="{{ $room->harga_per_malam }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="tersedia" {{ $room->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                                    <option value="dipesan" {{ $room->status == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                                                    <option value="perbaikan" {{ $room->status == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="deskripsi" class="form-control" rows="2">{{ $room->deskripsi }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">Belum ada data kamar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH KAMAR -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Foto Kamar</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor Kamar</label>
                            <input type="text" name="nomor_kamar" class="form-control" placeholder="101" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipe Kamar</label>
                            <select name="tipe_kamar" class="form-select" required>
                                <option value="standard">Standard</option>
                                <option value="superior">Superior</option>
                                <option value="deluxe">Deluxe</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga Per Malam (Rp)</label>
                            <input type="number" name="harga_per_malam" class="form-control" placeholder="300000" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="dipesan">Dipesan</option>
                                <option value="perbaikan">Perbaikan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>
