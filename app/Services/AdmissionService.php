<?php

namespace App\Services;

use App\Models\Admission;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdmissionTestScheduled;

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

        public function scheduleTestAndNotify(Admission $admission, array $data)
        {
        $testDetails = [
            'test_date' => $data['test_date'],
            'test_time' => $data['test_time'],
            'venue' => $data['venue'],
        ];

        // Send email notification
        Mail::to($admission->parent_email)
            ->send(new AdmissionTestScheduled($admission, $testDetails));

        return $testDetails; 
    }

    public function findAdmissionById(int $id): ?Admission
    {
        return Admission::find($id);
    }
}