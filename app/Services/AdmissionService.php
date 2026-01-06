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

        public function scheduleTestAndNotify(Admission $admission)
    {
        $testDetails = [
            'test_date' => $admission->test_date ?? now()->addDays(7),
            'test_time' => $admission->test_time ?? '10:00 AM',
            'venue' => $admission->venue ?? 'School Campus',
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