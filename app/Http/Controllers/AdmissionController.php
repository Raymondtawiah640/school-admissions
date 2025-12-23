<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Http\Requests\AdmissionRequest;
use App\Http\Requests\UpdateAdmissionStatusRequest;
use App\Services\AdmissionService;

class AdmissionController extends Controller
{
    public function __construct(
        private AdmissionService $admissionService
    ){}
    
    public function store(AdmissionRequest $request)
    {
        $admission = $this->admissionService->createAdmission($request->validated());
        return response()->json([
            'message' => 'Admission application submitted successfully.',
            'data' => $admission 
        ], 201);
    }

    
    public function index()
    {
        return response()->json([
            'data' => $this->admissionService->listAdmission()
        ], 200);
    }

    public function updateStatus(UpdateAdmissionStatusRequest $request, $id)
    {
        $admission = $this->admissionService->updateStatus($id, $request->validated()); 

        return response()->json([
            'message' => 'Admission status updated successfully.',
            'data' => $admission
        ], 200);
    }
}
