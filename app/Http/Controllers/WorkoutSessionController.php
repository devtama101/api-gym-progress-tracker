<?php

namespace App\Http\Controllers;

use App\Models\WorkoutSession;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkoutSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = WorkoutSession::all();
        if (! $data || $data->count() === 0) {
            return Response::success([
                'message' => 'No workout session found',
                'data' => [],
            ], 200);
        }

        return Response::success([
            'message' => 'Workout sessions retrieved successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'program_id' => 'required|integer|exists:programs,id',
            'date' => 'required|date',
            'duration' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'exercises' => 'required|array',
            'exercises.*.exercise_id' => 'required|integer|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            'exercises.*.weight' => 'required|numeric|min:0',
            'exercises.*.notes' => 'nullable|string',
        ]);
        if ($validated->fails()) {
            return Response::failed([
                'message' => 'Validation Error',
                'error' => $validated->errors()->first(),
            ], 422);
        }

        $data = WorkoutSession::create([
            'user_id' => $request->user()->id,
            'program_id' => $request->input('program_id'),
            'date' => $request->input('date'),
            'duration' => $request->input('duration'),
            'notes' => $request->input('notes') ?? '',
            'exercises' => json_encode($request->input('exercises')),
        ]);

        return Response::success([
            'message' => 'Workout session created successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = WorkoutSession::find($id);
        if (! $data) {
            return Response::failed([
                'message' => 'Workout session not found',
            ], 404);
        }

        return Response::success([
            'message' => 'Workout session retrieved successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = WorkoutSession::find($id);
        if (! $data) {
            return Response::failed([
                'message' => 'Workout session not found',
            ], 404);
        }
        $validated = Validator::make($request->all(), [
            'program_id' => 'sometimes|required|integer|exists:programs,id',
            'date' => 'sometimes|required|date',
            'duration' => 'sometimes|required|integer|min:1',
            'notes' => 'sometimes|nullable|string',
            'exercises' => 'sometimes|required|array',
            'exercises.*.exercise_id' => 'required_with:exercises|integer|exists:exercises,id',
            'exercises.*.sets' => 'required_with:exercises|integer|min:1',
            'exercises.*.reps' => 'required_with:exercises|integer|min:1',
            'exercises.*.weight' => 'required_with:exercises|numeric|min:0',
            'exercises.*.notes' => 'nullable|string',
        ]);
        if ($validated->fails()) {
            return Response::failed([
                'message' => 'Validation Error',
                'error' => $validated->errors()->first(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = WorkoutSession::find($id);
        if (! $data) {
            return Response::failed([
                'message' => 'Workout session not found',
            ], 404);
        }

        $data->delete();

        return Response::success([
            'message' => 'Workout session deleted successfully',
        ], 200);
    }
}
