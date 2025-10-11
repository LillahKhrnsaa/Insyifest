<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Member - Cikampek Swimming Club</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <style> 
        body { font-family: 'Nunito', sans-serif; } 
    </style>
</head>
<body class="bg-sky-50">

    <div class="py-6 bg-white shadow-sm">
        <div class="container mx-auto text-center">
            <a href="/" class="inline-block">
                <img src="{{ asset('images/logocsc.png') }}" alt="Logo Cikampek Swimming Club" class="h-16 w-auto">
            </a>
        </div>
    </div>

    <div class="flex items-center justify-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl p-8 sm:p-10 space-y-8 bg-white rounded-2xl shadow-2xl">

            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-slate-900">Form Pendaftaran Member Baru</h1>
                <p class="mt-2 text-slate-600">Selamat datang! Ayo bergabung dengan keluarga besar CSC.</p>
            </div>

            @if(session('status'))
                <div class="p-4 text-center text-sm text-green-800 bg-green-100 rounded-lg" role="alert">
                    <p class="font-bold text-lg">🎉 Pendaftaran Berhasil! 🎉</p>
                    <p class="mt-1">{{ session('status') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 text-sm text-red-800 bg-red-100 rounded-lg font-medium" role="alert">{{ session('error') }}</div>
            @endif

            <form class="mt-8 space-y-10" action="{{ route('member.register.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <fieldset class="space-y-6">
                    <legend class="text-xl font-bold text-cyan-600 flex items-center gap-2">
                        <span class="bg-cyan-500 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold">1</span>
                        Data Diri Calon Perenang
                    </legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required class="w-full px-4 py-2 mt-1 border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500" value="{{ old('name') }}">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700">Nomor Telepon (Orang Tua/Wali)</label>
                            <input id="phone" name="phone" type="tel" required class="w-full px-4 py-2 mt-1 border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500" value="{{ old('phone') }}">
                            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                            <input id="birth_date" name="birth_date" type="date" required class="w-full px-4 py-2 mt-1 border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500" value="{{ old('birth_date') }}">
                            @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-slate-700">Jenis Kelamin</label>
                            <select id="gender" name="gender" required class="w-full px-4 py-2 mt-1 bg-white border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                <option value="" disabled selected>Pilih...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="space-y-6">
                    <legend class="text-xl font-bold text-cyan-600 flex items-center gap-2">
                        <span class="bg-cyan-500 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold">2</span>
                        Info Akun (Untuk Login Orang Tua/Wali)
                    </legend>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                            <input id="password" name="password" type="password" required class="w-full px-4 py-2 mt-1 border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-4 py-2 mt-1 border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                        </div>
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend class="text-xl font-bold text-cyan-600 flex items-center gap-2">
                        <span class="bg-cyan-500 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold">3</span>
                        Pilih Paket & Pembayaran
                    </legend>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <div class="space-y-6">
                            <div>
                                <label for="training_package_id" class="block text-sm font-medium text-slate-700">Pilih Paket Latihan</label>
                                <select id="training_package_id" name="training_package_id" required class="w-full px-4 py-2 mt-1 bg-white border border-slate-300 rounded-lg shadow-sm focus:ring-cyan-500 focus:border-cyan-500">
                                    <option value="" data-price="0" disabled selected>Pilih Paket...</option>
                                    @foreach ($trainingPackages as $package)
                                        <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('training_package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('training_package_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="bg-slate-50 p-6 rounded-lg text-center">
                                <p class="text-sm font-medium text-slate-600">Total Biaya Pendaftaran:</p>
                                <p id="total_price" class="text-4xl font-extrabold text-cyan-600 mt-1">Rp 0</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                             <div class="p-6 border rounded-lg bg-slate-50">
                                <p class="text-sm font-medium text-slate-700">Silakan transfer ke salah satu rekening:</p>
                                <ul class="mt-3 space-y-3 text-sm">
                                    @forelse ($bankAccounts as $account)
                                        <li class="p-3 bg-white border rounded-md">
                                            <span class="font-bold text-slate-800">{{ $account->bank_name }}:</span> {{ $account->account_number }}
                                            <br>
                                            <span class="text-xs text-slate-500">a.n. {{ $account->account_holder }}</span>
                                        </li>
                                    @empty
                                        <li>Tidak ada rekening tersedia saat ini.</li>
                                    @endforelse
                                </ul>
                            </div>
                            <div x-data="{ fileName: '' }">
                                <label for="proof_of_payment" class="block text-sm font-medium text-slate-700 mb-1">Upload Bukti Transfer</label>
                                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-slate-900/25 px-6 py-10">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" /></svg>
                                        <div class="mt-4 flex text-sm leading-6 text-slate-600">
                                            <label for="proof_of_payment" class="relative cursor-pointer rounded-md bg-white font-semibold text-cyan-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-cyan-600 focus-within:ring-offset-2 hover:text-cyan-500">
                                                <span>Pilih file</span>
                                                <input id="proof_of_payment" name="proof_of_payment" type="file" required class="sr-only" @change="fileName = $event.target.files[0].name">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs leading-5 text-slate-600">PNG, JPG, JPEG hingga 2MB</p>
                                        <p x-show="fileName" x-text="fileName" class="text-sm font-semibold text-green-600 mt-2"></p>
                                    </div>
                                </div>
                                @error('proof_of_payment') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="pt-6">
                    <button type="submit" class="group relative flex w-full justify-center rounded-full border border-transparent bg-cyan-500 py-3 px-4 text-lg font-bold text-white hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition-colors">
                        <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Daftar & Kirim Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('training_package_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
            document.getElementById('total_price').textContent = formattedPrice;
        });
        
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('training_package_id').dispatchEvent(new Event('change'));
        });
    </script>

</body>
</html>