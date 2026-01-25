<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind / CSS bebas --}}
    <link rel="stylesheet" href="https://cdn.tailwindcss.com">
</head>
<body class="bg-slate-100">

<div class="max-w-3xl mx-auto py-10 px-6 bg-white rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-2">
        {{ $form->title }}
    </h1>

    <p class="text-slate-600 mb-6">
        {{ $form->description }}
    </p>

    {{-- FORM --}}
    <form
        method="POST"
        action="{{ route('form.external.submit', $form->slug) }}"
    >
        @csrf

        {{-- =====================
         SCHEDULE + COACH
        ===================== --}}
        <div class="mb-6">
            <label class="block font-semibold mb-2">
                Pilih Jadwal & Pelatih
            </label>

            <select name="schedule_coach_id" required>
                <option value="">-- Pilih --</option>

                @foreach ($form->schedules as $schedule)
                    @foreach ($schedule->coaches as $scheduleCoach)
                        @if ($scheduleCoach->remaining_quota > 0)
                            <option value="{{ $scheduleCoach->id }}">
                                {{ $schedule->day }},
                                {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                ({{ $schedule->time }})
                                - {{ $scheduleCoach->coach->name }}
                                [Sisa: {{ $scheduleCoach->remaining_quota }}]
                            </option>
                        @endif
                    @endforeach
                @endforeach
            </select>

        </div>

        {{-- =====================
         FORM FIELDS
        ===================== --}}
        @foreach ($form->fields as $field)
            <div class="mb-4">
                <label class="block font-medium mb-1">
                    {{ $field->label }}
                    @if ($field->is_required)
                        <span class="text-red-500">*</span>
                    @endif
                </label>

                @switch($field->type)

                    @case('text')
                        <input
                            type="text"
                            name="answers[{{ $field->id }}]"
                            class="w-full border rounded px-3 py-2"
                            @required($field->is_required)
                        >
                        @break

                    @case('textarea')
                        <textarea
                            name="answers[{{ $field->id }}]"
                            class="w-full border rounded px-3 py-2"
                            @required($field->is_required)
                        ></textarea>
                        @break

                    @case('select')
                        <select
                            name="answers[{{ $field->id }}]"
                            class="w-full border rounded px-3 py-2"
                            @required($field->is_required)
                        >
                            <option value="">-- Pilih --</option>
                            @foreach (json_decode($field->options ?? '[]', true) as $opt)
                                <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('checkbox')
                        @foreach (json_decode($field->options ?? '[]', true) as $opt)
                            <label class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="answers[{{ $field->id }}][]"
                                    value="{{ $opt }}"
                                >
                                {{ $opt }}
                            </label>
                        @endforeach
                        @break

                @endswitch
            </div>
        @endforeach

        {{-- SUBMIT --}}
        <button
            type="submit"
            class="mt-6 w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700"
        >
            Daftar Sekarang
        </button>

    </form>

</div>

</body>
</html>
