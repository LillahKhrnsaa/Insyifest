<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100">

<div class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-slate-200 p-8 space-y-8">

        <div class="text-center space-y-2">
            <img src="{{ asset('images/logocsc.png') }}" alt="Logo Form" class="h-24 w-auto mx-auto mb-6">
            <h1 class="text-3xl font-extrabold text-slate-800">
                {{ $form->title }}
            </h1>
            <p class="text-slate-600 max-w-xl mx-auto">
                {{ $form->description }}
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('form.external.submit', $form->slug) }}"
            class="space-y-6">
            @csrf

            <div class="p-5 bg-slate-50 rounded-xl border border-slate-200">
                <label class="block font-semibold text-slate-700 mb-2">
                    Pilih Jadwal & Pelatih
                </label>

                <select
                    name="schedule_coach_id"
                    required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">-- Pilih Jadwal --</option>

                    @foreach ($form->schedules as $schedule)
                        @foreach ($schedule->coaches as $scheduleCoach)
                            @if ($scheduleCoach->remaining_quota > 0)
                                <option value="{{ $scheduleCoach->id }}">
                                    {{ $schedule->day }},
                                    {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                    ({{ $schedule->time }})
                                    - {{ $scheduleCoach->coach->name }}
                                    | Sisa: {{ $scheduleCoach->remaining_quota }}
                                </option>
                            @endif
                        @endforeach
                    @endforeach
                </select>
            </div>

            @foreach ($form->fields as $field)
                <div>
                    <label class="block font-medium text-slate-700 mb-1">
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
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                            @break

                        @case('textarea')
                            <textarea
                                name="answers[{{ $field->id }}]"
                                rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)></textarea>
                            @break

                        @case('select')
                            <select
                                name="answers[{{ $field->id }}]"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                                <option value="">-- Pilih --</option>
                                @foreach (json_decode($field->options ?? '[]', true) as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                            @break

                        @case('checkbox')
                            <div class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                @foreach (json_decode($field->options ?? '[]', true) as $opt)
                                    <label class="flex items-center gap-3">
                                        <input
                                            type="checkbox"
                                            name="answers[{{ $field->id }}][]"
                                            value="{{ $opt }}"
                                            class="h-5 w-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <span class="text-slate-700">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                    @endswitch
                </div>
            @endforeach

            <button
                type="submit"
                class="w-full py-4 rounded-xl bg-indigo-600 text-white text-lg font-bold hover:bg-indigo-700 transition shadow-lg">
                Daftar Sekarang
            </button>

        </form>

    </div>

</div>

</body>
</html>
