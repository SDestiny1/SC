<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Comment extends Model
{
    protected $connection = 'mongodb'; // si usas conexión mongodb en config/database.php
    protected $collection = 'comments';

    protected $fillable = ['post_id', 'usuario_id', 'texto', 'fecha'];

    protected $dates = ['fecha'];
}
