<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    // GET /api/v1/games/{game}/players
    public function players(Game $game)
    {
        return response()->json(['data' => $game->players]);
    }
    // GET /api/v1/games
    public function index()
    {
        return response()->json([
            'data' => Game::latest()->get(),
        ]);
    }

    // POST /api/v1/games
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'max_players' => ['nullable', 'integer', 'min:1'],
        ]);

        // adapte à ta structure: code, admin_id, status, etc.
        $game = Game::create([
            'code'       => strtoupper(str()->random(6)),
            'admin_id'   => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null, // ou null si pas d’auth
            'status'     => 'waiting',
            // + éventuellement $validated si colonnes existantes
        ]);

        return response()->json(['data' => $game], 201);
    }

    // GET /api/v1/games/{game}
    public function show(Game $game)
    {
        return response()->json(['data' => $game]);
    }

    // POST /api/v1/games/{game}/join
    public function join(Request $request, Game $game)
    {
        $validated = $request->validate([
            'player' => ['required', 'string', 'max:255'],
        ]);

        $player = $game->players()->create([
            'name'      => $validated['player'],
            'user_id'   => optional(\Illuminate\Support\Facades\Auth::user())->id, // optionnel
            'joined_at' => now(),
        ]);

        return response()->json([
            'message' => 'Player joined',
            'data'    => $player,
        ], 201);
    }

    // PUT/PATCH /api/v1/games/{game}
    public function update(Request $request, Game $game)
    {
        $game->update($request->only(['status'])); // adapte
        return response()->json(['data' => $game]);
    }

    // DELETE /api/v1/games/{game}
    public function destroy(Game $game)
    {
        $game->delete();
        return response()->json([], 204);
    }
}
