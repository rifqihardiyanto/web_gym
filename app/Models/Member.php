<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Member extends Model
{
    use HasFactory;

    protected $fillable = ['type_member', 'name', 'phone', 'exp', 'email', 'payment'];

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            // Mengambil 4 digit terakhir dari phone, atau isi dengan default "0000" jika phone kosong
            $lastFourDigits = substr($model->phone, -4) ?: "0000";

            // Menggabungkan 4 digit terakhir dari phone dengan ID unik yang di-generate
            $uniqueId = strtoupper(bin2hex(random_bytes(3))); // bisa diganti jumlah byte sesuai kebutuhan
            $model->id_member = $uniqueId . $lastFourDigits;
        });
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'type_member', 'id');
    }
}
