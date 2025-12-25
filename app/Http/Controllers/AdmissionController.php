<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Http\Requests\AdmissionRequest;
use App\Http\Requests\UpdateAdmissionStatusRequest;
use App\Services\AdmissionService;
use App\Services\UserService;

class AdmissionController extends Controller
{
    public function __construct(
        private AdmissionService $admissionService,
        private UserService $userService
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
        if (!$this->userService->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => $this->admissionService->listAdmission()
        ], 200);
    }

    public function updateStatus(UpdateAdmissionStatusRequest $request, $id)
    {
        if (!$this->userService->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $admission = $this->admissionService->updateStatus($id, $request->validated());

        return response()->json([
            'message' => 'Admission status updated successfully.',
            'data' => $admission
        ], 200);
    }
}
