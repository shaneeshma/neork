<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_detail extends Model
{
    use HasFactory;
    protected $table = 'user_details';
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class);
    }
}