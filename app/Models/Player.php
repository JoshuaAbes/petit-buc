<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['game_id', 'user_id', 'name', 'joined_at'];
    protected $casts = ['joined_at' => 'datetime'];

    public function game()
    {
        return $this->belongsTo(\App\Models\Game::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
