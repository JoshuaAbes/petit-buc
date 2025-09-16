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

        // Vérifier qu'il y a au moins 6 catégories
        $totalCategories = \App\Models\Category::count();
        if ($totalCategories < 6) {
            return response()->json([
                'message' => 'Pas assez de catégories pour démarrer une manche.'
            ], 400);
        }

        // Tirer 6 catégories aléatoires (on stocke les ids)
        $categories = \App\Models\Category::inRandomOrder()->limit(6)->pluck('id')->toArray();

        $round = Round::create([
            'game_id' => $game->id,
            'letter' => $letter,
            'categories' => $categories,
            'status' => 'running',
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
    $round = $game->rounds()->where('status', 'running')->latest('started_at')->first();
        if (!$round) {
            return response()->json(['message' => 'No active round'], 404);
        }
        // Récupère les catégories complètes
    $categories = \App\Models\Category::whereIn('id', $round->categories)->get();
    $data = $round->toArray();
    $data['categories'] = $categories;
    // Numéro du round = position dans la partie
    $data['number'] = $round->game->rounds()->where('started_at', '<=', $round->started_at)->count();
    return response()->json(['data' => $data]);
    }
    // Méthodes inutiles supprimées pour alléger le contrôleur

    // Valide toutes les réponses d'une manche
    public function validateAnswers(Game $game, Round $round)
    {
        // On valide toutes les réponses du round
        $count = $round->answers()->update(['is_valid' => true]);
        // On peut aussi finir la manche
        $round->status = 'finished';
        $round->ended_at = now();
        $round->save();
        return response()->json([
            'message' => "Réponses validées et manche terminée",
            'answers_validated' => $count,
        ]);
    }

    // Récapitulatif des réponses par catégorie et joueur
    public function recap(Game $game, Round $round)
    {
        if ($round->status !== 'finished') {
            return response()->json(['message' => 'La manche n\'est pas terminée'], 400);
        }
        // Récupère les catégories de la manche
        $categories = \App\Models\Category::whereIn('id', $round->categories)->get();
        // Récupère tous les joueurs de la partie
        $players = $game->players()->get();
        // Récupère toutes les réponses de la manche
        $answers = $round->answers()->get();

        // Structure : [catégorie][joueur] = réponse
        $recap = [];
        foreach ($categories as $category) {
            $catRecap = [];
            foreach ($players as $player) {
                $answer = $answers->where('category_id', $category->id)->where('player_id', $player->id)->first();
                $catRecap[] = [
                    'player' => $player->name,
                    'answer' => $answer ? $answer->answer : null,
                ];
            }
            $recap[] = [
                'category' => $category->name,
                'players' => $catRecap,
            ];
        }
        return response()->json([
            'letter' => $round->letter,
            'categories' => $recap,
        ]);
    }
}
