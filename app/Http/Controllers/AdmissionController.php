<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;

class AdmissionController extends Controller
{
    /**
     * Public: Submit admission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'date_of_birth'   => 'required|date',
            'gender'          => 'required|string|max:50',
            'class_applied'   => 'required|string|max:100',
            'parent_name'     => 'required|string|max:255',
            'parent_contact'  => 'required|string|max:100',
            'address'         => 'required|string',
            'interest'        => 'nullable|string|max:255',
            'remarks'         => 'nullable|string',
        ]);

        // Backend controls status
        $validated['status'] = 'pending';

        $admission = Admission::create($validated);

        return response()->json([
            'message' => 'Admission application submitted successfully.',
            'data'    => $admission
        ], 201);
    }

    /**
     * Admin only: View all admissions
     */
    public function index(Request $request)
    {
        // Authenticated user (via middleware)
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(Admission::all(), 200);
    }

    /**
     * Admin only: Approve or reject
     */
    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $admission = Admission::findOrFail($id);
        $admission->update($validated);

        return response()->json([
            'message' => 'Admission status updated successfully.',
            'data'    => $admission
        ], 200);
    }
}
