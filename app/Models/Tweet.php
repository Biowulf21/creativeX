<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tweet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tweet_body',
        'likes_count',
        'retweet_count',
        'is_retweet',
    ];

    protected $guarded = [
        'replying_to',
        'user_id',
        'likes_count',
        'retweets_count',
        'is_retweet'
    ];



}
