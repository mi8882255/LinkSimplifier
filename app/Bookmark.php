<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmarks';

    public function comments() {
        return $this->hasMany('App\Comment');
    }
}
