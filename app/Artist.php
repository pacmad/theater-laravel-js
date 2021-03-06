<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['firstname', 'lastname', 'agency_id'];
    // One to Many inverse relationship
    public function types()
    {
        return $this->belongsToMany('App\Type');
    }
    // One to Many inverse relationship
    public function agencies()
    {
        return $this->belongsTo('App\Agency', 'agency_id');
    }
}
