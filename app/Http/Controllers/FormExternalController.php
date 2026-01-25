<?php

namespace App\Http\Controllers;

use App\Models\RegistrationAnswer;
use App\Models\RegistrationForm;
use App\Models\RegistrationSubmission;
use App\Models\ScheduleCoach;
use App\Services\Registration\RegistrationSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormExternalController extends Controller
{
    public function show(string $slug)
    {
        $form = RegistrationForm::query()
            ->where('slug', $slug)
            ->with([
                'schedules.coaches.coach',
                'fields',
            ])
            ->first();

        if (! $form) {
            abort(404);
        }

        if (! $form->is_active) {
            return view('form-externals.unactive-form', compact('form'));
        }

        return view('form-externals.active-form', compact('form'));
    }

    
    public function submit(
        Request $request,
        string $slug,
        RegistrationSubmissionService $service
    ) {
        $form = RegistrationForm::with('fields')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // VALIDATION tetap
        $rules = [
            'schedule_coach_id' => ['required', 'exists:schedule_coaches,id'],
        ];

        foreach ($form->fields as $field) {
            $key = "answers.{$field->id}";

            if ($field->is_required) {
                $rules[$key] = 'required';
            }
        }

        $validated = $request->validate($rules);

        // 👉 SATU PINTU SUBMIT
        $service->submit(
            $form,
            (int) $validated['schedule_coach_id'],
            $validated['answers'] ?? []
        );

        return back()->with('success', 'Pendaftaran berhasil 🎉');
    }

}
