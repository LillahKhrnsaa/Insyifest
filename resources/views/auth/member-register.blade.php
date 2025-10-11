<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Member - Cikampek Swimming Club</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen py-12">
        <div class="w-full max-w-4xl p-8 space-y-8 bg-white rounded-xl shadow-lg">

            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Form Pendaftaran Member Baru</h1>
                <p class="mt-2 text-sm text-gray-600">Silakan isi data diri dan lakukan pembayaran.</p>
            </div>

            <!-- Menampilkan pesan sukses setelah redirect -->
            @if(session('status'))
                <div class="p-4 text-center text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <p class="font-bold">🎉 Pendaftaran Berhasil! 🎉</p>
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            <!-- Menampilkan pesan error global -->
            @if(session('error'))
                <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- PENTING: Gunakan 'enctype' untuk file upload -->
            <form class="mt-8 space-y-6" action="{{ route('member.register.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Kolom Kiri: Form Isian -->
                    <div class="md:col-span-2 space-y-6">
                        <!-- ... (Field Nama, Telepon, Tgl Lahir, Gender tetap sama) ... -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input id="name" name="name" type="text" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm" value="{{ old('name') }}">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input id="phone" name="phone" type="tel" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm" value="{{ old('phone') }}">
                            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                             <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input id="birth_date" name="birth_date" type="date" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm" value="{{ old('birth_date') }}">
                                @error('birth_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select id="gender" name="gender" required class="w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm">
                                    <option value="" disabled selected>Pilih...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                         <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input id="password" name="password" type="password" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm">
                                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Info Pembayaran -->
                    <div class="space-y-6">
                        <div class="p-4 border rounded-lg bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-800">Detail Pembayaran</h3>
                             <div class="mt-4">
                                <label for="training_package_id" class="block text-sm font-medium text-gray-700">Pilih Paket Latihan</label>
                                <select id="training_package_id" name="training_package_id" required class="w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm">
                                    <option value="" data-price="0" disabled selected>Pilih Paket...</option>
                                    @foreach ($trainingPackages as $package)
                                        <option value="{{ $package->id }}" data-price="{{ $package->price }}" {{ old('training_package_id') == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('training_package_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-600">Total Biaya Pendaftaran:</p>
                                <p id="total_price" class="text-2xl font-bold text-indigo-600">Rp 0</p>
                            </div>

                            <div class="mt-4 pt-4 border-t">
                                <p class="text-sm font-medium text-gray-600">Silakan transfer ke salah satu rekening:</p>
                                <ul class="mt-2 space-y-2 text-sm">
                                    @forelse ($bankAccounts as $account)
                                        <li>
                                            <span class="font-semibold">{{ $account->bank_name }}:</span> {{ $account->account_number }}
                                            <br>
                                            <span class="text-xs text-gray-500">a.n. {{ $account->account_holder }}</span>
                                        </li>
                                    @empty
                                        <li>Tidak ada rekening tersedia.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label for="proof_of_payment" class="block text-sm font-medium text-gray-700">Upload Bukti Transfer</label>
                            <input id="proof_of_payment" name="proof_of_payment" type="file" required class="block w-full text-sm text-gray-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                             <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Max: 2MB.</p>
                            @error('proof_of_payment') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="relative flex justify-center w-full px-4 py-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md group hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Daftar dan Kirim Bukti Pembayaran
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
        
        // Trigger change event on page load if a package was already selected (e.g., due to validation error)
        document.getElementById('training_package_id').dispatchEvent(new Event('change'));
    </script>

</body>
</html>