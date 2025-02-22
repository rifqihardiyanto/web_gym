<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member()
    {
        return $this->hasMany(Member::class);
    }

    public function Daftarmember()
    {
        return $this->hasMany(DaftarMember::class);
    }
    
    public function nonMemberReport()
    {
        return $this->hasMany(nonMemberReport::class);
    }

    public function MemberReport()
    {
        return $this->hasMany(MemberReport::class);
    }
}
