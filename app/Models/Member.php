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
            do {
                $randomNumber = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT);
                $model->id_member = 'PG' . $randomNumber;
            } while (Member::where('id_member', $model->id_member)->exists());
        });
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'type_member', 'id');
    }
}
