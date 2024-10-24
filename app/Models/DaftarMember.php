<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarMember extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'id_member', 'type_member', 'payment'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'type_member', 'id');
    }
}
