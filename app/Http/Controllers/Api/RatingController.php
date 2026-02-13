<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'score' => ['required', 'string', 'in:bad,neutral,good'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $rating = Rating::create($validated);

        return response()->json([
            'id' => $rating->id,
            'created_at' => $rating->created_at->toIso8601String(),
        ], 201);
    }
}
