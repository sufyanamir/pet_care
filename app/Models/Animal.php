<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animals';

    protected $primaryKey = 'animal_id';

    protected $fillable = [
        'added_user_id',
        'animal_name',
        'animal_image',
    ];

    public $timestamps = true;

}
