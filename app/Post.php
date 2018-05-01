<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_title', 'post_body','post_img'
    ];

    public function user()
    {
    	$this->belongsTo('App\User');
    }
}
