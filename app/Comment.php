<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function house()
    {
        return $this->belongsTo('App\House');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function replies(){
        return $this->hasMany('App\CommentReply');
    }
}
