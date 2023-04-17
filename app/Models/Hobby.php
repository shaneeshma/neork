<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;
    protected $table = 'hobbies';
    public function users()
    {
        return $this->belongsToMany(User_detail::class);
    }
}