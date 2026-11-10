<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'nama',
        'email',
        'nomor_telepon',
        'alamat',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'customer_id');
    }
}
