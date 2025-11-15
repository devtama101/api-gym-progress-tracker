<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Utils\Response;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Program::all();
        if (! $data || $data->count() === 0) {
            return Response::success([
                'message' => 'No programs found',
                'data' => [],
            ], 200);
        }

        return Response::success([
            'message' => 'Programs retrieved successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = Program::create([
            'name' => $request->input('name'),
            'description' => $request->input('description') ?? '',
        ]);

        return Response::success([
            'message' => 'Program created successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($program)
    {
        $data = Program::find($program);

        if (! $data) {
            return Response::failed([
                'message' => 'Not found',
                'error' => 'Program does not exist',
            ], 404);
        }

        return Response::success([
            'data' => $data,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($program, Request $request)
    {
        $data = Program::find($program);

        if (! $data) {
            return Response::failed([
                'message' => 'Not found',
                'error' => 'Program does not exist',
            ], 404);
        }

        $data->update([
            'name' => $request->input('name', $data->name),
            'description' => $request->input('description', $data->description),
        ]);

        return Response::success([
            'message' => 'Program updated successfully',
            'data' => $data,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return Response::success([
            'message' => 'Program deleted successfully',
        ], 200);
    }
}
