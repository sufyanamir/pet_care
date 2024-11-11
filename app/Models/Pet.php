<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';

    protected $primaryKey = 'pet_id';

    protected $fillable = [
        'added_user_id',
        'animal_id',
        'breed_id',
        'pet_name',
        'pet_age',
        'pet_gender',
        'pet_height',
        'pet_weight',
        'pet_variation',
        'pet_apearance_desc',
        'pet_nature_desc',
        'pet_image',
        'check_dob',
        'check_feed',
        'pet_dob',
        'pet_status',
    ];

    public $timestamps = true;

    public function getPetImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    public function breed()
    {
        return $this->belongsTo(AnimalBreed::class, 'breed_id');
    }

}
