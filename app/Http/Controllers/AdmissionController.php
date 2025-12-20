<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\User;

class AdmissionController extends Controller
{
    // Anyone can submit
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender'=> 'required|string|max:50',
            'class_applied' => 'required|string|max:100',
            'parent_name' => 'required|string|max:255',
            'parent_contact' => 'required|string|max:100',
            'address' => 'required|string',
            'interest' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'required|string|in:pending,approved,rejected' 
        ]);    

        $admission = Admission::create($validated);

        return response()->json([
            'message' => 'Admission application submitted successfully.',
            'data' => $admission
        ], 201);
    }

    // Only admin can view all submissions
        public function index(Request $request)
    {
        // Get the Bearer token from the request header
        $token = $request->bearerToken();

        // Find admin using token
        $admin = User::where('role', 'admin')
                    ->whereHas('tokens', function ($q) use ($token) {
                        $q->where('id', $token); // or adjust for your token column
                    })
                    ->first();

        if (!$admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(Admission::all(), 200);
    }

    // Only admin can approve/reject
    public function updateStatus(Request $request, $id)
    {
        // Get admin token
        $token = $request->bearerToken();
        $admin = User::where('role', 'admin')->first(); // For now, single admin

        if (!$admin || $token !== '1|H18oLO8zp6Bo5qCyfPS77VAXqNNVKlCXLJvjqtyG18e60910') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the new status
        $validated = $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        // Find the admission
        $admission = Admission::find($id);
        if (!$admission) {
            return response()->json(['message' => 'Admission not found'], 404);
        }

        // Update the status
        $admission->update([
            'status' => $validated['status']
        ]);

        return response()->json([
            'message' => 'Admission status updated successfully.',
            'data' => $admission
        ], 200);
    }
}
