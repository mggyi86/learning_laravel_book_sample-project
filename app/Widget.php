<?php

namespace App;

class Widget extends SuperModel
{
    //
    protected $fillable = ['name', 'slug', 'user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
