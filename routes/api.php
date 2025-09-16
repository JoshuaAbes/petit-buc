<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\RoundController;
use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\ScoreboardController;

Route::prefix('v1')->group(function () {
    // Games
    Route::apiResource('games', GameController::class);
    Route::post('games/{game}/players', [GameController::class, 'join']);
    Route::get('games/{game}/players', [GameController::class, 'players']);

    // Rounds
    Route::post('games/{game}/rounds/start', [RoundController::class, 'start']);
    Route::get('games/{game}/rounds/current', [RoundController::class, 'current']);
    Route::post('games/{game}/rounds/{round}/validate', [RoundController::class, 'validateAnswers']);

    // Answers
    Route::post('games/{game}/rounds/{round}/answers', [AnswerController::class, 'store']);

    // Scoreboard
    Route::get('games/{game}/scoreboard', [ScoreboardController::class, 'show']);
});
