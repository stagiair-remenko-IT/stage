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
            'score' => ['required', 'string', 'in:very_bad,bad,neutral,good,very_good'],
            'location' => ['nullable', 'string', 'max:255'],
            'age_range' => ['nullable', 'string', 'in:18-24,25-34,35-44,45-54,55+'],
            'gender' => ['nullable', 'string', 'in:male,female,other,prefer_not_to_say'],
        ]);

        $validated['score_numeric'] = [
            'very_bad' => 1, 'bad' => 2, 'neutral' => 3, 'good' => 4, 'very_good' => 5,
        ][$validated['score']];

        $rating = Rating::create($validated);

        return response()->json([
            'id' => $rating->id,
            'created_at' => $rating->created_at->toIso8601String(),
        ], 201);
    }

    public function update(Request $request, Rating $rating): JsonResponse
    {
        $validated = $request->validate([
            'age_range' => ['nullable', 'string', 'in:18-24,25-34,35-44,45-54,55+'],
            'gender' => ['nullable', 'string', 'in:male,female,other,prefer_not_to_say'],
        ]);

        $rating->update($validated);

        return response()->json(['ok' => true]);
    }
}
