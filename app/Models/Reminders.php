<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminders extends Model
{
    use HasFactory;

    protected $table = 'reminders';

    protected $primaryKey = 'reminder_id';

    protected $fillable = [
        'added_user_id',
        'reminder_title',
        'reminder_date',
        'reminder_time',
    ];

    public $timestamps = true;

}
