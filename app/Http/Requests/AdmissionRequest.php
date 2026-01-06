<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return True;
    }

    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string|max:50',
            'class_applied' => 'required|string|max:100',
            'parent_name' => 'required|string|max:255',
            'parent_email' => 'required|email', 
            'parent_contact' => 'required|string|max:100',
            'address' => 'required|string',
            'interest' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ];
    }
}
