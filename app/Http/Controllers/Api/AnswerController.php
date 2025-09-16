<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
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
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'category_id' => 'required|exists:categories,id',
            'answer' => 'required|string|max:255',
        ]);

        $answer = \App\Models\Answer::create([
            'round_id' => $roundId,
            'player_id' => $validated['player_id'],
            'category_id' => $validated['category_id'],
            'answer' => $validated['answer'],
            'is_valid' => false, // à valider plus tard
        ]);

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
        $validated = $request->validate([
            'is_valid' => 'required|boolean',
        ]);

        $answer = \App\Models\Answer::where('id', $answerId)
            ->where('round_id', $roundId)
            ->firstOrFail();

        $answer->is_valid = $validated['is_valid'];
        $answer->save();

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
