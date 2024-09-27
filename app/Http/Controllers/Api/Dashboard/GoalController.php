<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    // Display a listing of the goals
    public function index()
    {
        $goal = Goal::first();
        if (!$goal) {
            return response()->json([
               'status' => 'error',
               'message' => 'Goal not found'
            ], 404); // Return 404 if goal not found
        }
        return response()->json($goal);
    }

    // Store a newly created goal
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0', // Validate the amount
        ]);
        $goal = Goal::first();
        if ($goal) {
            return response()->json([
               'status' => 'error',
               'message' => 'Goal already exists'
            ], 404); // Return 404 if goal not found
        }

        $goal = Goal::create($request->all());
        return response()->json($goal, 201); // Return the created goal with status 201
    }


    // Update a specific goal
    public function update(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0', // Validate the amount
        ]);

        $goal = Goal::first();
        if (!$goal) {
            return response()->json([
               'status' => 'error',
               'message' => 'Goal not found'
            ], 404); // Return 404 if goal not found
        }
        $goal->update($request->all());

        return response()->json($goal);
    }

    // Remove the specified goal
    public function destroy()
    {
        $goal = Goal::first();
        if (!$goal) {
            return response()->json([
               'status' => 'error',
               'message' => 'Goal not found'
            ], 404); // Return 404 if goal not found
        }
        $goal->delete();

        return response()->json(['message' => 'Goal deleted successfully']);
    }
}
