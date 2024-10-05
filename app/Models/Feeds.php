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
    ];

    public $timestamps = true;

    public function getFeedImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }

}
