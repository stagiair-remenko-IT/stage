<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rating_id' => ['nullable', 'integer', 'exists:ratings,id'],
            'text' => ['required', 'string', 'max:500'],
        ]);

        $feedback = Feedback::create($validated);

        return response()->json([
            'id' => $feedback->id,
            'created_at' => $feedback->created_at->toIso8601String(),
        ], 201);
    }
}
