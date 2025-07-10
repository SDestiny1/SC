<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class CalendarioEscolar extends Model
{
    protected $collection = 'calendarioEscolar';
    protected $connection = 'mongodb';
    protected $guarded = [];
}
