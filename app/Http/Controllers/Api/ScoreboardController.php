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
        $game = \App\Models\Game::with(['players', 'rounds.answers'])->findOrFail($gameId);
        $scores = [];
        // Regrouper les joueurs par id pour éviter les doublons
        $uniquePlayers = $game->players->unique('id');
        foreach ($uniquePlayers as $player) {
            $scores[$player->id] = [
                'player' => $player->name,
                'score' => 0,
            ];
        }

        // Pour chaque round de la partie
        foreach ($game->rounds as $round) {
            // Pour chaque catégorie du round (cast auto)
            foreach ($round->categories as $catName) {
                // Récupérer les réponses valides pour cette catégorie
                $answers = $round->answers->filter(function($a) use ($catName) {
                    return $a->is_valid && $a->category && $a->category->name === $catName;
                });
                // Compter les doublons
                $grouped = $answers->groupBy('answer');
                foreach ($grouped as $sameAnswers) {
                    if ($sameAnswers->count() === 1) {
                        $answer = $sameAnswers->first();
                        $scores[$answer->player_id]['score']++;
                    }
                    // Si plusieurs joueurs ont le même mot, personne ne marque de point
                }
            }
        }
        // Retourne le scoreboard
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
