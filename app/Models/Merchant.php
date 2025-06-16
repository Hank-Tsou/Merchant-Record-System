<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'phone',
        'email',
        'two_factor_enabled',
        'category',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
