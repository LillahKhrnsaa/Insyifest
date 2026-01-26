<?php

namespace App\Http\Controllers;

use App\Models\RegistrationForm;
use App\Services\Registration\RegistrationSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormExternalController extends Controller
{
    public function show(string $slug)
    {
        $form = RegistrationForm::query()
            ->where('slug', $slug)
            ->first();

        if (! $form) {
            abort(404);
        }

        if (! $form->is_active) {
            return view('form-externals.unactive-form', compact('form'));
        }

        // ✅ Fresh load dari DB (ga pake cache)
        $form = RegistrationForm::where('slug', $slug)
            ->with([
                'schedules.coaches.coach.user',
                'fields'
            ])
            ->first();

        // ✅ Return dengan no-cache headers
        return response()
            ->view('form-externals.active-form', compact('form'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
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

        $service->submit(
            $form,
            (int) $validated['schedule_coach_id'],
            $validated['answers'] ?? []
        );

        // ✅ Redirect ke form lagi biar fresh load
        return redirect()->route('form.external.show', $slug)
            ->with('success', 'Pendaftaran berhasil 🎉');
    }

}