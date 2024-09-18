<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalBreed extends Model
{
    use HasFactory;

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    protected $table = 'animal_breed';

    protected $primaryKey = 'breed_id';

    protected $fillable = [
        'added_user_id',
        'animal_id',
        'breed_name',
        'breed_image',
    ];

    public $timestamps = true;

    public function getBreedImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }


}
