<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'gif_id',
    ];

    /**
     * Get the user for the fav.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the gif for the fav.
     */
    public function gif()
    {
        return $this->belongsTo('App\Gif');
    }
}
