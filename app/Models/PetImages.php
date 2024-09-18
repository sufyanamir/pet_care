<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetImages extends Model
{
    use HasFactory;

    protected $table = 'pet_images';

    protected $primaryKey = 'pet_image_id';

    protected $fillable = [
        'added_user_id',
        'pet_id',
        'pet_image',
    ];

    public $timestamps = true;

}
