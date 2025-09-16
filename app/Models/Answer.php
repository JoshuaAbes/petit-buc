<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'round_id', 'player_id', 'category_id', 'answer', 'is_valid'
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
