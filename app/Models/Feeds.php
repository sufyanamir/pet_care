<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    use HasFactory;

    protected $table = 'feeds';
    
    protected $primaryKey = 'feed_id';

    protected $fillable = [
        'added_user_id',
        'pet_id',
        'feed_post',
        'post_desc',
        'feed_likes',
    ];

    public $timestamps = true;

    public function getFeedPostAttribute($value)
    {
        return $value ? asset($value) : null;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'pet_id');
    }

}
