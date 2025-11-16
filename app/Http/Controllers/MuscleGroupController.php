<?php

namespace App\Http\Controllers;

use App\Models\MuscleGroup;
use App\Utils\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MuscleGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MuscleGroup::all();
        if (! $data || $data->count() === 0) {
            return Response::success([
                'message' => 'No muscle group found',
                'data' => [],
            ], 200);
        }

        return Response::success([
            'message' => 'Muscle groups retrieved successfully',
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
        ]);
        if ($validated->fails()) {
            return Response::failed([
                'message' => 'Validation Error',
                'error' => $validated->errors()->first(),
            ], 422);
        }

        $data = MuscleGroup::create([
            'name' => $request->input('name'),
            'description' => $request->input('description') ?? '',
        ]);

        return Response::success([
            'message' => 'Muscle group created successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = MuscleGroup::find($id);
        if (! $data) {
            return Response::failed([
                'message' => 'Muscle group not found',
            ], 404);
        }

        return Response::success([
            'message' => 'Muscle group retrieved successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $muscleGroup = MuscleGroup::find($id);
        if (! $muscleGroup) {
            return Response::failed([
                'message' => 'Muscle group not found',
            ], 404);
        }

        $validated = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);
        if ($validated->fails()) {
            return Response::failed([
                'message' => 'Validation Error',
                'error' => $validated->errors()->first(),
            ], 422);
        }

        $muscleGroup->update($request->only(['name', 'description']));

        return Response::success([
            'message' => 'Muscle group updated successfully',
            'data' => $muscleGroup,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $muscleGroup = MuscleGroup::find($id);
        if (! $muscleGroup) {
            return Response::failed([
                'message' => 'Muscle group not found',
            ], 404);
        }

        $muscleGroup->delete();

        return Response::success([
            'message' => 'Muscle group deleted successfully',
        ], 200);
    }
}
