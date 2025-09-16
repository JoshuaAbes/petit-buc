<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'code', 'admin_id', 'status'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
