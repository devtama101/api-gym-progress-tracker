<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Exercise::all();
        if (! $data || $data->count() === 0) {
            return Response::success([
                'message' => 'No exercise found',
                'data' => [],
            ], 200);
        }

        return Response::success([
            'message' => 'Exercises retrieved successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'muscle_group_id' => 'required|integer|exists:muscle_groups,id',
        ]);
        if ($validated->fails()) {
            return Response::failed([
                'message' => 'Validation Error',
                'error' => $validated->errors()->first(),
            ], 422);
        }

        $data = Exercise::create([
            'name' => $request->input('name'),
            'description' => $request->input('description') ?? '',
            'muscle_group_id' => $request->input('muscle_group_id'),
        ]);

        return Response::success([
            'message' => 'Exercise created successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Exercise::find($id);

        if (! $data) {
            return Response::failed([
                'message' => 'Not found',
                'error' => 'Exercise does not exist',
            ], 404);
        }

        return Response::success([
            'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Exercise::find($id);

        if (! $data) {
            return Response::failed([
                'message' => 'Not found',
                'error' => 'Exercise does not exist',
            ], 404);
        }

        $validated = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'muscle_group_id' => 'sometimes|required|integer|exists:muscle_groups,id',
        ]);
        if ($validated->fails()) {
            return Response::failed([
                'message' => 'Validation Error',
                'error' => $validated->errors()->first(),
            ], 422);
        }

        $data->update([
            'name' => $request->input('name', $data->name),
            'description' => $request->input('description', $data->description),
            'muscle_group_id' => $request->input('muscle_group_id', $data->muscle_group_id),
        ]);

        return Response::success([
            'message' => 'Exercise updated successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Exercise::find($id);

        if (! $data) {
            return Response::failed([
                'message' => 'Not found',
                'error' => 'Exercise does not exist',
            ], 404);
        }

        $data->delete();

        return Response::success([
            'message' => 'Exercise deleted successfully',
        ], 200);
    }
}
