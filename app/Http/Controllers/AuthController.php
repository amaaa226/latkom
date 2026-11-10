<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Customer;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.index')
                : redirect()->route('rooms.index');
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('rooms.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function indexRooms(Request $request)
{
    $query = Room::query();

    if ($request->filled(['tanggal_masuk', 'tanggal_keluar'])) {
        $in  = $request->tanggal_masuk;
        $out = $request->tanggal_keluar;

        $query->whereDoesntHave('reservations', function ($q) use ($in, $out) {
            $q->where(function ($sub) use ($in, $out) {
                $sub->whereBetween('tanggal_masuk', [$in, $out])
                    ->orWhereBetween('tanggal_keluar', [$in, $out])
                    ->orWhere(fn($n) => $n->where('tanggal_masuk', '<=', $in)->where('tanggal_keluar', '>=', $out));
            })->whereIn('status', ['pending', 'dikonfirmasi', 'check_in']);
        });
    }

    if ($request->filled('tipe_kamar')) {
        $query->where('tipe_kamar', $request->tipe_kamar);
    }

    $rooms = $query->get();

    return view('welcome', compact('rooms'));
}

    public function createReservation(Request $request, $room_id)
    {
        $room = Room::findOrFail($room_id);
        $checkIn = Carbon::parse($request->tanggal_masuk ?? now());
        $checkOut = Carbon::parse($request->tanggal_keluar ?? now()->addDay());

        $durationNights = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice = $durationNights * $room->harga_per_malam;

        return view('transaksi', compact('room', 'checkIn', 'checkOut', 'durationNights', 'totalPrice'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string',
            'email'          => 'required|email',
            'nomor_telepon'  => 'required|string',
            'room_id'        => 'required|exists:rooms,id',
            'tanggal_masuk'  => 'required|date',
            'tanggal_keluar' => 'required|date|after:tanggal_masuk',
        ]);

        $checkIn  = Carbon::parse($request->tanggal_masuk);
        $checkOut = Carbon::parse($request->tanggal_keluar);
        $room     = Room::findOrFail($request->room_id);

        $durationNights = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice     = $durationNights * $room->harga_per_malam;

        $customer = Customer::firstOrCreate(
            ['email' => $request->email],
            ['nama' => $request->nama, 'nomor_telepon' => $request->nomor_telepon, 'alamat' => $request->alamat]
        );

        $reservation = Reservasi::create([
            'kode_booking'    => 'HTL-' . strtoupper(uniqid()),
            'customer_id'     => $customer->id,
            'room_id'         => $room->id,
            'tanggal_masuk'   => $checkIn,
            'tanggal_keluar'  => $checkOut,
            'durasi_menginap' => $durationNights,
            'total_biaya'     => $totalPrice,
            'status'          => 'pending',
        ]);

        return redirect()->route('reservations.confirmation', $reservation->id);
    }

    public function confirmationReservation($id)
    {
        $reservation = Reservasi::with(['customer', 'room'])->findOrFail($id);
        return view('konfirmasi', compact('reservation'));
    }

    public function historyReservation()
    {
        $customer = Customer::where('email', Auth::user()->email)->first();

        $reservations = $customer
            ? Reservasi::with('room')->where('customer_id', $customer->id)->latest()->get()
            : collect();

        return view('riwayat', compact('reservations'));
    }

    public function adminIndex()
    {
        $rooms = Room::latest()->get();
        return view('admin', compact('rooms'));
    }

    public function storeRoom(Request $request)
{
    $validated = $request->validate([
        'foto'            => 'required|image|max:2048',
        'nomor_kamar'     => 'required|unique:rooms',
        'tipe_kamar'      => 'required|in:standard,superior,deluxe',
        'harga_per_malam' => 'required|numeric',
        'status'          => 'required|in:tersedia,dipesan,perbaikan',
        'deskripsi'       => 'nullable|string',
    ]);

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $validated['foto'] = 'images/' . $filename;
    }

    Room::create($validated);
    return back()->with('success', 'Kamar berhasil ditambahkan.');
}

public function updateRoom(Request $request, Room $room)
{
    $validated = $request->validate([
        'foto'            => 'nullable|image|max:2048',
        'nomor_kamar'     => 'required|unique:rooms,nomor_kamar,' . $room->id,
        'tipe_kamar'      => 'required|in:standard,superior,deluxe',
        'harga_per_malam' => 'required|numeric',
        'status'          => 'required|in:tersedia,dipesan,perbaikan',
        'deskripsi'       => 'nullable|string',
    ]);

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $filename);
        $validated['foto'] = 'images/' . $filename;
    }

    $room->update($validated);
    return back()->with('success', 'Kamar berhasil diperbarui.');
}

    public function destroyRoom(Room $room)
    {
        $room->delete();
        return back()->with('success', 'Kamar berhasil dihapus.');
    }

    public function adminReservations()
    {
        $reservations = Reservasi::with(['customer', 'room'])->latest()->get();
        return view('admin_reservasi', compact('reservations'));
    }

    public function confirmPayment($id)
    {
        $reservation = Reservasi::findOrFail($id);
        $reservation->update(['status' => 'dikonfirmasi']);

        if ($reservation->room) {
            $reservation->room->update(['status' => 'dipesan']);
        }

        return back()->with('success', 'Status pembayaran berhasil dikonfirmasi dan kamar telah ditandai dipesan!');
    }
}
