<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Http\Requests\AdmissionRequest;
use App\Http\Requests\UpdateAdmissionStatusRequest;

class AdmissionController extends Controller
{
    
    public function store(AdmissionRequest $request)
    {
        $admission = Admission::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Admission application submitted successfully.',
            'data' => $admission 
        ], 201);
    }

    
    public function index()
    {
        return response()->json([
            'data' => Admission::latest()->get()
        ], 200);
    }

    public function updateStatus(UpdateAdmissionStatusRequest $request, $id)
    {
        $admission = Admission::findOrFail($id);
        $admission->update($request->validated());

        return response()->json([
            'message' => 'Admission status updated successfully.',
            'data' => $admission
        ], 200);
    }
}
