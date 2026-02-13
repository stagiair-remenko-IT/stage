<?php

use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\RatingController;
use Illuminate\Support\Facades\Route;

Route::post('/ratings', [RatingController::class, 'store']);
Route::patch('/ratings/{rating}', [RatingController::class, 'update']);
Route::post('/feedback', [FeedbackController::class, 'store']);
