<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gif extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'views',
        'path',
        'tag'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'views' => 0,
    ];

    /**
     * Get the tags for the gif.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    /**
     * Get the favs for the gif.
     */
    public function favorites()
    {
        return $this->hasMany('App\Favorite');
    }
}
