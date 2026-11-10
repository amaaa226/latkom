<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'foto',
        'nomor_kamar',
        'tipe_kamar',
        'harga_per_malam',
        'status',
        'deskripsi',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservasi::class, 'room_id');
    }
}
