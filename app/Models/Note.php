<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'title',
        'body',
        'type',
        'status',
        'merchant_id',
        'created_by',
        'assigned_to',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
