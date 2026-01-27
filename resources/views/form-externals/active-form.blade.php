<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $form->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
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

        @if (session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-green-700 font-medium text-center">
                    ✅ {{ session('success') }}
                </p>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-red-700 font-medium">❌ Terdapat kesalahan:</p>
                <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('form.external.submit', $form->slug) }}"
            class="space-y-6">
            @csrf

            {{-- FORM FIELDS --}}
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
                                value="{{ old('answers.'.$field->id) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                            @break

                        @case('email')
                            <input
                                type="email"
                                name="answers[{{ $field->id }}]"
                                value="{{ old('answers.'.$field->id) }}"
                                placeholder="contoh@email.com"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                            @break

                        @case('tel')
                            <input
                                type="tel"
                                name="answers[{{ $field->id }}]"
                                value="{{ old('answers.'.$field->id) }}"
                                placeholder="08123456789"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                            @break

                        @case('number')
                            <input
                                type="number"
                                name="answers[{{ $field->id }}]"
                                value="{{ old('answers.'.$field->id) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                            @break

                        @case('date')
                            <input
                                type="date"
                                name="answers[{{ $field->id }}]"
                                value="{{ old('answers.'.$field->id) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                            @break

                        @case('textarea')
                            <textarea
                                name="answers[{{ $field->id }}]"
                                rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>{{ old('answers.'.$field->id) }}</textarea>
                            @break

                        @case('select')
                            <select
                                name="answers[{{ $field->id }}]"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                @required($field->is_required)>
                                <option value="">-- Pilih --</option>
                                @foreach (json_decode($field->options ?? '[]', true) as $opt)
                                    <option value="{{ $opt }}" {{ old('answers.'.$field->id) == $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                            @break

                        @case('radio')
                            <div class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                @foreach (json_decode($field->options ?? '[]', true) as $opt)
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="answers[{{ $field->id }}]"
                                            value="{{ $opt }}"
                                            {{ old('answers.'.$field->id) == $opt ? 'checked' : '' }}
                                            class="h-5 w-5 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                            @required($field->is_required)>
                                        <span class="text-slate-700">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('checkbox')
                            <div class="space-y-2 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                @foreach (json_decode($field->options ?? '[]', true) as $opt)
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="answers[{{ $field->id }}][]"
                                            value="{{ $opt }}"
                                            {{ in_array($opt, old('answers.'.$field->id, [])) ? 'checked' : '' }}
                                            class="h-5 w-5 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <span class="text-slate-700">{{ $opt }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                    @endswitch

                    @error('answers.'.$field->id)
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
            
            {{-- ========================================
                SCHEDULE SELECTION
                Conditional: Grouping vs Non-Grouping
            ======================================== --}}
            
            @php
                // Check if ANY schedule has grouping
                $hasGrouping = $form->schedules->whereNotNull('schedule_group')->isNotEmpty();
            @endphp

            @if ($hasGrouping)
                {{-- ✅ GROUPED VIEW (Radio Buttons per Schedule Group) --}}
                <div class="p-5 bg-slate-50 rounded-xl border border-slate-200 space-y-6">
                    <label class="block font-semibold text-slate-700 mb-2">
                        Pilih Jadwal & Pelatih <span class="text-red-500">*</span>
                    </label>

                    @php
                        $groupedSchedules = $form->schedules->groupBy('schedule_group');
                    @endphp

                    @foreach ($groupedSchedules as $groupName => $schedules)
                        <div class="p-4 bg-white rounded-lg border border-slate-300 shadow-sm">
                            <h3 class="font-bold text-lg text-indigo-700 mb-3">
                                📅 {{ $groupName }}
                            </h3>

                            @foreach ($schedules as $schedule)
                                <div class="mb-4 pl-4 border-l-4 border-indigo-300">
                                    <p class="font-medium text-slate-700 mb-2">
                                        {{ $schedule->day }}, 
                                        {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                        ({{ $schedule->time }}) 
                                        @if ($schedule->location)
                                            - <span class="text-indigo-600">{{ $schedule->location }}</span>
                                        @endif
                                    </p>

                                    <div class="space-y-2">
                                        @foreach ($schedule->coaches as $scheduleCoach)
                                            @if ($scheduleCoach->remaining_quota > 0)
                                                <label class="flex items-center gap-3 cursor-pointer hover:bg-indigo-50 p-2 rounded transition">
                                                    <input 
                                                        type="radio" 
                                                        name="schedule_coach_id" 
                                                        value="{{ $scheduleCoach->id }}"
                                                        {{ old('schedule_coach_id') == $scheduleCoach->id ? 'checked' : '' }}
                                                        class="h-5 w-5 text-indigo-600 border-slate-300 focus:ring-indigo-500"
                                                        required>
                                                    <span class="text-slate-700">
                                                        {{ $scheduleCoach->coach?->user?->name ?? 'Coach tidak tersedia' }}
                                                        | <span class="text-green-600 font-medium">Sisa: {{ $scheduleCoach->remaining_quota }}</span>
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    @error('schedule_coach_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            @else
                {{-- ✅ NON-GROUPED VIEW (Original Dropdown) --}}
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
                                    <option value="{{ $scheduleCoach->id }}" {{ old('schedule_coach_id') == $scheduleCoach->id ? 'selected' : '' }}>
                                        {{ $schedule->day }},
                                        {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                        ({{ $schedule->time }})
                                        - {{ $scheduleCoach->coach?->user?->name ?? 'Coach tidak tersedia' }}
                                        | Sisa: {{ $scheduleCoach->remaining_quota }}
                                    </option>
                                @endif
                            @endforeach
                        @endforeach
                    </select>

                    @error('schedule_coach_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

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