<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScoreboardController extends Controller
{
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

    // GET /api/v1/games/{game}/scoreboard
    public function show($gameId)
    {
        // Récupère le jeu avec ses joueurs et les réponses de chaque manche
        $game = \App\Models\Game::with(['players', 'rounds.answers'])->findOrFail($gameId);
        $scores = [];
        // Initialise le score de chaque joueur à 0
        $uniquePlayers = $game->players->unique('id');
        foreach ($uniquePlayers as $player) {
            $scores[$player->id] = [
                'player' => $player->name,
                'score' => 0,
            ];
        }

        // Parcourt chaque manche du jeu
        foreach ($game->rounds as $round) {
            // Pour chaque réponse validée de la manche, ajoute 1 point au joueur
            foreach ($round->answers as $answer) {
                // Si la réponse est validée par le maître du jeu
                if ($answer->is_valid && isset($scores[$answer->player_id])) {
                    $scores[$answer->player_id]['score']++;
                }
            }
        }
        // Retourne le scoreboard sous forme de tableau
        return response()->json(array_values($scores));
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
