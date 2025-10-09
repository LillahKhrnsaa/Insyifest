@extends('layouts.app')

@section('title', $form->title)

@section('content')
<div class="w-full max-w-2xl bg-white/95 backdrop-blur-sm shadow-xl rounded-lg p-8 border border-white/30 mt-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">{{ $form->title }}</h1>

    @if($form->description)
        <p class="text-gray-600 mb-6 text-center">{{ $form->description }}</p>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-5 border border-green-300 text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('form.submit', $form->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @foreach ($form->fields as $field)
            <div class="bg-white border border-gray-200 rounded-md p-4 shadow-sm hover:shadow-md hover:border-indigo-400 transition">
                <label class="block font-medium text-gray-800 mb-1">
                    {{ $field['label'] }}
                    @if(!empty($field['required'])) 
                        <span class="text-red-500">*</span>
                    @endif
                </label>

                @switch($field['type'])
                    @case('text')
                    @case('email')
                    @case('number')
                    @case('url')
                    @case('password')
                    @case('tel')
                        <input 
                            type="{{ $field['type'] }}" 
                            name="{{ $field['name'] }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2" 
                            placeholder="{{ $field['placeholder'] ?? '' }}" 
                            {{ !empty($field['required']) ? 'required' : '' }}
                        >
                        @break

                    @case('textarea')
                        <textarea 
                            name="{{ $field['name'] }}" 
                            rows="3"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            {{ !empty($field['required']) ? 'required' : '' }}
                        ></textarea>
                        @break

                    @case('select')
                        <select 
                            name="{{ $field['name'] }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                            {{ !empty($field['required']) ? 'required' : '' }}>
                            <option value="">-- Pilih --</option>
                            @foreach(explode(',', $field['options'] ?? '') as $option)
                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('checkbox')
                        <div class="flex flex-wrap gap-3">
                            @foreach(explode(',', $field['options'] ?? '') as $option)
                                <label class="inline-flex items-center space-x-2">
                                    <input 
                                        type="checkbox" 
                                        name="{{ $field['name'] }}[]" 
                                        value="{{ trim($option) }}" 
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span>{{ trim($option) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @break

                    @case('radio')
                        <div class="flex flex-wrap gap-3">
                            @foreach(explode(',', $field['options'] ?? '') as $option)
                                <label class="inline-flex items-center space-x-2">
                                    <input 
                                        type="radio" 
                                        name="{{ $field['name'] }}" 
                                        value="{{ trim($option) }}" 
                                        class="text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <span>{{ trim($option) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @break

                    @case('date')
                    @case('datetime')
                        <input 
                            type="{{ $field['type'] === 'datetime' ? 'datetime-local' : 'date' }}" 
                            name="{{ $field['name'] }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2"
                            {{ !empty($field['required']) ? 'required' : '' }}
                        >
                        @break

                    @case('file')
                        <input 
                            type="file" 
                            name="{{ $field['name'] }}" 
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2"
                            {{ !empty($field['required']) ? 'required' : '' }}
                        >
                        @break
                @endswitch

                @error($field['name'])
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <div class="pt-4 text-center">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-md font-semibold hover:from-purple-700 hover:to-indigo-700 transition">
                Kirim Jawaban
            </button>
        </div>
    </form>
</div>
@endsection
