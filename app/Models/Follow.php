<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Follow extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'follower_user_id',
        'following_user_id',
    ];

    public function follower()
    {
        return User::find($this->follower_user_id) ?? null;
    }

    public function following()
    {
        return User::find($this->following_user_id) ?? null;
    }
}
