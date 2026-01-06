<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
    'name',
    'date_of_birth',
    'gender',
    'class_applied',
    'parent_name',
    'parent_email',
    'parent_contact',
    'address',
    'interest',
    'remarks',
    'status'
];

}
