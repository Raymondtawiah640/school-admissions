<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdmissionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // middleware will handle admin check
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:approved,rejected',
        ];
    }
}

