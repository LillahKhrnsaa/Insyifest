@extends('layouts.app')

@section('title', 'Registrasi Member - Cikampek Swimming Club')

@section('content')
<div class="bg-gradient-to-br from-sky-50 to-cyan-50 min-h-screen pt-20">
    <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-10 left-10 w-40 h-40 bg-cyan-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
            <div class="absolute top-1/3 right-20 w-60 h-60 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
            <div class="absolute bottom-20 left-1/3 w-52 h-52 bg-teal-300 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
        </div>

        <div class="w-full max-w-4xl p-8 sm:p-10 space-y-8 bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/50 fade-in relative z-10">
            <!-- Success Header -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-500 text-white shadow-lg mb-6">
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Form Pendaftaran Member Baru</h1>
                <p class="mt-3 text-lg text-slate-600 max-w-2xl mx-auto">Bergabunglah dengan keluarga besar Cikampek Swimming Club dan mulailah petualangan renang Anda!</p>
            </div>

            <!-- Status Messages -->
            @if(session('status'))
                <div class="p-6 text-center text-green-800 bg-gradient-to-r from-green-100 to-emerald-100 rounded-2xl border border-green-200 shadow-sm" role="alert">
                    <div class="flex items-center justify-center mb-3">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <p class="font-bold text-xl">🎉 Pendaftaran Berhasil! 🎉</p>
                    <p class="mt-2 text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 text-center text-red-800 bg-gradient-to-r from-red-100 to-pink-100 rounded-2xl border border-red-200 font-medium" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Registration Form -->
            <form class="mt-8 space-y-10" action="{{ route('member.register.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1: Personal Data -->
                <fieldset class="space-y-6 p-6 bg-gradient-to-br from-sky-50 to-cyan-50 rounded-2xl border border-cyan-100">
                    <legend class="text-xl font-bold text-cyan-700 flex items-center gap-3">
                        <span class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold shadow-md">1</span>
                        Data Diri Calon Perenang
                    </legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap">
                            @error('name') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">Nomor Telepon (Orang Tua/Wali)</label>
                            <input id="phone" name="phone" type="tel" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                   value="{{ old('phone') }}"
                                   placeholder="Contoh: 081234567890">
                            @error('phone') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                            <input id="birth_date" name="birth_date" type="date" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                   value="{{ old('birth_date') }}">
                            @error('birth_date') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kelamin</label>
                            <select id="gender" name="gender" required 
                                    class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200">
                                <option value="" disabled selected>Pilih Jenis Kelamin...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                <!-- Section 2: Account Info -->
                <fieldset class="space-y-6 p-6 bg-gradient-to-br from-sky-50 to-cyan-50 rounded-2xl border border-cyan-100">
                    <legend class="text-xl font-bold text-cyan-700 flex items-center gap-3">
                        <span class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold shadow-md">2</span>
                        Info Akun (Untuk Login Orang Tua/Wali)
                    </legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                            <input id="password" name="password" type="password" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                   placeholder="Minimal 8 karakter">
                            @error('password') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 bg-white/80 backdrop-blur-sm"
                                   placeholder="Ketik ulang password">
                        </div>
                    </div>
                </fieldset>
                
                <!-- Section 3: Package & Payment -->
                <fieldset class="p-6 bg-gradient-to-br from-sky-50 to-cyan-50 rounded-2xl border border-cyan-100">
                    <legend class="text-xl font-bold text-cyan-700 flex items-center gap-3">
                        <span class="bg-gradient-to-br from-cyan-500 to-blue-600 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold shadow-md">3</span>
                        Pilih Paket & Pembayaran
                    </legend>
                    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                        <!-- Package Selection -->
                        <div class="space-y-6">
                            <div>
                                <label for="training_package_id" class="block text-sm font-semibold text-slate-700 mb-2">Pilih Paket Latihan</label>
                                <select id="training_package_id" name="training_package_id" required 
                                        class="w-full px-4 py-3 bg-white/80 backdrop-blur-sm border border-slate-300 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200">
                                    <option value="" data-price="0" disabled selected>Pilih Paket...</option>
                                    @foreach ($trainingPackages as $package)
                                        <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('training_package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('training_package_id') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Total Price Display -->
                            <div class="bg-gradient-to-br from-white to-slate-50 p-6 rounded-xl border border-slate-200 text-center shadow-sm">
                                <p class="text-sm font-medium text-slate-600">Total Biaya Pendaftaran:</p>
                                <p id="total_price" class="text-4xl font-extrabold bg-gradient-to-r from-cyan-500 to-blue-600 bg-clip-text text-transparent mt-2">Rp 0</p>
                                <p class="text-xs text-slate-500 mt-2">*Sudah termasuk biaya pendaftaran dan paket latihan</p>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="space-y-6">
                            <!-- Bank Accounts -->
                            <div class="p-6 border border-slate-200 rounded-xl bg-gradient-to-br from-white to-slate-50 shadow-sm">
                                <h3 class="text-lg font-bold text-slate-800 mb-3 flex items-center gap-2">
                                    <svg class="h-5 w-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    Transfer Pembayaran
                                </h3>
                                <p class="text-sm text-slate-600 mb-4">Silakan transfer ke salah satu rekening berikut:</p>
                                <div class="space-y-3">
                                    @forelse ($bankAccounts as $account)
                                        <div class="p-4 bg-white border border-slate-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="font-bold text-slate-800">{{ $account->bank_name }}</span>
                                                    <p class="text-lg font-mono text-cyan-700 mt-1">{{ $account->account_number }}</p>
                                                    <p class="text-xs text-slate-500 mt-1">a.n. {{ $account->account_holder }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-slate-500 py-4">
                                            Tidak ada rekening tersedia saat ini.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div x-data="{ fileName: '' }" class="space-y-3">
                                <label for="proof_of_payment" class="block text-sm font-semibold text-slate-700">Upload Bukti Transfer</label>
                                <div class="mt-2 flex justify-center rounded-xl border-2 border-dashed border-slate-300 px-6 py-10 transition-all duration-200 hover:border-cyan-400 hover:bg-cyan-50/50">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="mt-4 flex text-sm leading-6 text-slate-600 justify-center">
                                            <label for="proof_of_payment" class="relative cursor-pointer rounded-md bg-white font-semibold text-cyan-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-cyan-600 focus-within:ring-offset-2 hover:text-cyan-500 transition-colors duration-200">
                                                <span>Pilih file</span>
                                                <input id="proof_of_payment" name="proof_of_payment" type="file" required class="sr-only" @change="fileName = $event.target.files[0]?.name || ''" accept=".png,.jpg,.jpeg,.pdf">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs leading-5 text-slate-500">PNG, JPG, JPEG, PDF (maks. 2MB)</p>
                                        <p x-show="fileName" x-text="'File terpilih: ' + fileName" class="text-sm font-semibold text-green-600 mt-3 bg-green-50 px-3 py-2 rounded-lg"></p>
                                    </div>
                                </div>
                                @error('proof_of_payment') <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </fieldset>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" class="group relative flex w-full justify-center rounded-full border border-transparent bg-gradient-to-r from-cyan-500 to-blue-600 py-4 px-6 text-lg font-bold text-white hover:from-cyan-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="h-6 w-6 mr-3 transition-transform group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Daftar & Kirim Bukti Pembayaran
                    </button>
                    <p class="text-center text-sm text-slate-500 mt-4">
                        Dengan mendaftar, Anda menyetujui 
                        <a href="#" class="text-cyan-600 hover:text-cyan-700 font-medium underline">syarat dan ketentuan</a> 
                        yang berlaku.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Price calculation
    document.getElementById('training_package_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const formattedPrice = new Intl.NumberFormat('id-ID', { 
            style: 'currency', 
            currency: 'IDR', 
            minimumFractionDigits: 0 
        }).format(price);
        document.getElementById('total_price').textContent = formattedPrice;
    });
    
    // Initialize price on page load
    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('training_package_id').dispatchEvent(new Event('change'));
    });
</script>
@endsection