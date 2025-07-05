<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Teacher extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'teachers';

    protected $fillable = ['name', 'email', 'specialty', 'subject'];
}
