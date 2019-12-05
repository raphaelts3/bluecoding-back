<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'query'
    ];

    /**
     * Get the user for the history.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
