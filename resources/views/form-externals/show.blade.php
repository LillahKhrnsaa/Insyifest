@extends('layouts.app')

@section('title', $form->title)

@section('content')
{{-- Latar belakang gradien dan elemen dekoratif --}}
<div class="bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-50 min-h-screen pt-20">
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        
        {{-- Pola Latar Belakang Animasi --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-40 h-40 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-1/3 right-20 w-60 h-60 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-20 left-1/3 w-52 h-52 bg-sky-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
        </div>

        {{-- Kontainer Utama Form dengan Efek Kaca --}}
        <div class="w-full max-w-4xl p-8 sm:p-10 space-y-8 bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/50 relative z-10">
            
            {{-- Header Form --}}
            <div class="text-center">
                <img src="{{ asset('images/logocsc.png') }}" alt="Logo Cikampek Swimming Club" class="h-24 w-auto">
                <h1 class="text-4xl font-extrabold text-slate-900 bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
                    {{ $form->title }}
                </h1>
                @if($form->description)
                    <p class="mt-3 text-lg text-slate-600 max-w-2xl mx-auto">{{ $form->description }}</p>
                @endif
            </div>

            {{-- Pesan Sukses --}}
            @if(session('success'))
                <div class="p-6 text-center text-green-800 bg-gradient-to-r from-green-100 to-emerald-100 rounded-2xl border border-green-200 shadow-sm" role="alert">
                    <p class="font-bold text-xl">🎉 Berhasil Terkirim! 🎉</p>
                    <p class="mt-2 text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('form.submit', $form->slug) }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-8">
                @csrf

                <div class="space-y-6">
                @foreach ($form->fields as $field)
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
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
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                    placeholder="{{ $field['placeholder'] ?? '' }}" 
                                    {{ !empty($field['required']) ? 'required' : '' }}
                                >
                                @break

                            @case('textarea')
                                <textarea 
                                    name="{{ $field['name'] }}" 
                                    rows="4"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                    placeholder="{{ $field['placeholder'] ?? '' }}"
                                    {{ !empty($field['required']) ? 'required' : '' }}
                                ></textarea>
                                @break

                            @case('select')
                                <select 
                                    name="{{ $field['name'] }}" 
                                    class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                    {{ !empty($field['required']) ? 'required' : '' }}>
                                    <option value="" disabled selected>-- {{ $field['placeholder'] ?? 'Pilih salah satu' }} --</option>
                                    @foreach(explode(',', $field['options'] ?? '') as $option)
                                        <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                    @endforeach
                                </select>
                                @break

                            @case('checkbox')
                                <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    @foreach(explode(',', $field['options'] ?? '') as $option)
                                        <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200 cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                name="{{ $field['name'] }}[]" 
                                                value="{{ trim($option) }}" 
                                                class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            >
                                            <span class="text-slate-700">{{ trim($option) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('radio')
                                <div class="space-y-3 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                    @foreach(explode(',', $field['options'] ?? '') as $option)
                                        <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200 cursor-pointer">
                                            <input 
                                                type="radio" 
                                                name="{{ $field['name'] }}" 
                                                value="{{ trim($option) }}" 
                                                class="h-5 w-5 text-indigo-600 focus:ring-indigo-500"
                                                {{ !empty($field['required']) ? 'required' : '' }}
                                            >
                                            <span class="text-slate-700">{{ trim($option) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('date')
                            @case('datetime')
                                <input 
                                    type="{{ $field['type'] === 'datetime' ? 'datetime-local' : 'date' }}" 
                                    name="{{ $field['name'] }}" 
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                    {{ !empty($field['required']) ? 'required' : '' }}
                                >
                                @break

                            @case('file')
                                {{-- Input file gaya baru, memerlukan Alpine.js --}}
                                <div x-data="{ fileName: '' }">
                                    <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed border-slate-300 px-6 py-10 transition-all duration-200 hover:border-indigo-400 hover:bg-indigo-50/50">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-slate-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="mt-4 flex text-sm leading-6 text-slate-600 justify-center">
                                                <label for="{{ $field['name'] }}" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500 transition-colors duration-200">
                                                    <span>Pilih file</span>
                                                    <input 
                                                        id="{{ $field['name'] }}"
                                                        name="{{ $field['name'] }}" 
                                                        type="file" 
                                                        class="sr-only"
                                                        @change="fileName = $event.target.files[0]?.name || ''"
                                                        {{ !empty($field['required']) ? 'required' : '' }}
                                                    >
                                                </label>
                                                <p class="pl-1">atau seret dan lepas</p>
                                            </div>
                                            <p class="text-xs leading-5 text-slate-500">{{ $field['placeholder'] ?? 'PNG, JPG, PDF, dll.' }}</p>
                                            <p x-show="fileName" x-text="'File terpilih: ' + fileName" class="text-sm font-semibold text-green-600 mt-3 bg-green-50 px-3 py-2 rounded-lg"></p>
                                        </div>
                                    </div>
                                </div>
                                @break
                        @endswitch

                        @error($field['name'])
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
                </div>

                {{-- Tombol Submit --}}
                <div class="pt-6">
                    <button type="submit" class="group relative flex w-full justify-center rounded-full border border-transparent bg-gradient-to-r from-purple-600 to-indigo-600 py-4 px-6 text-lg font-bold text-white hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="h-6 w-6 mr-3 transition-transform group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection