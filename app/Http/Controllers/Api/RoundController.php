<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Round;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function start(Game $game)
    {
        // Générer une lettre aléatoire A-Z
        $letter = chr(rand(65, 90));

        // Tirer 6 catégories aléatoires
        $categories = \App\Models\Category::inRandomOrder()->limit(6)->pluck('name')->toArray();

        $round = Round::create([
            'game_id' => $game->id,
            'letter' => $letter,
            'categories' => $categories,
            'status' => 'active',
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Round started',
            'data' => $round,
        ], 201);
    }

    // Retourne le round actif d'une partie
    public function current(Game $game)
    {
        $round = $game->rounds()->where('status', 'active')->latest('started_at')->first();
        if (!$round) {
            return response()->json(['message' => 'No active round'], 404);
        }
        return response()->json($round);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
