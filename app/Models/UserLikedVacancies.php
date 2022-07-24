<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikedVacancies extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacancy_id',
    ];
}
