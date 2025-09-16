<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = [
        'game_id', 'letter', 'categories', 'started_at', 'ended_at', 'status'
    ];

    protected $casts = [
        'categories' => 'array',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
