<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    /**
     * Affiche la liste des réponses d'un round (avec joueur et catégorie).
     */
    public function index($gameId, $roundId)
    {
        $answers = \App\Models\Answer::with(['player', 'category'])
            ->where('round_id', $roundId)
            ->get();
        return response()->json($answers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $gameId, $roundId)
    {
        // Récupère les données JSON envoyées
        $data = json_decode($request->getContent(), true) ?? [];
        // Valide les champs obligatoires
        $validated = Validator::make($data, [
            'player_id' => 'required|exists:players,id',
            'category_id' => 'required|exists:categories,id',
            'answer' => 'required|string|max:255',
        ])->validate();

        // Crée la réponse en base, non validée par défaut
        $answer = \App\Models\Answer::create([
            'round_id' => $roundId,
            'player_id' => $validated['player_id'],
            'category_id' => $validated['category_id'],
            'answer' => $validated['answer'],
            'is_valid' => false, // à valider plus tard
        ]);

        // Retourne la réponse créée
        return response()->json($answer, 201);
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
     * Permet au maître du jeu de valider/invalider une réponse.
     */
    public function update(Request $request, $gameId, $roundId, $answerId)
    {
        // Valide le champ is_valid envoyé
        $validated = $request->validate([
            'is_valid' => 'required|boolean',
        ]);

        // Récupère la réponse à mettre à jour
        $answer = \App\Models\Answer::where('id', $answerId)
            ->where('round_id', $roundId)
            ->firstOrFail();

        // Met à jour la validation de la réponse
        $answer->is_valid = $validated['is_valid'];
        $answer->save();

        // Retourne la réponse modifiée
        return response()->json($answer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
