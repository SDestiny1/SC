<?php
// app/Models/Post.php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Post extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'posts';

    protected $fillable = ['title', 'author', 'content', 'image'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', '_id');
    }
}
