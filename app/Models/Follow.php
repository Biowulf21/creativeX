<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follow extends Model
{
    use HasFactory;
    use SoftDeletes;

    private $fillable = [
        'follower_user_id',
        'following_user_id',
    ];

    public function follower(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'follower_user_id');

    }

    public function following(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'following_user_id');
    }
}
