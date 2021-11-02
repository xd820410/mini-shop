<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    const administrator = 0;

    protected $fillable = [
        'user_id', 'right'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
