<?php

namespace   App\Services;

use App\Models\Admission;

class AdmissionService
{
    public function createAdmission(array $data): Admission{
        return Admission::create([
            ...$data,
            'status' => 'pending',
        ]);
    }

    public function listAdmission(){
        return Admission::latest()->get();
    }

    public function updateStatus(int $id, array $data): Admission{
        $admission = Admission::findOrFail($id);
        $admission->update($data);
        return $admission;
    }
}
