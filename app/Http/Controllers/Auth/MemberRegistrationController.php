<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Member;
use App\Models\TrainingPackage;
use App\Models\BankAccount;
use App\Models\PaymentHistory;
use App\Notifications\NewMemberRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class MemberRegistrationController extends Controller
{
    /**
     * Menampilkan form registrasi untuk member.
     */
    public function create()
    {
        $trainingPackages = TrainingPackage::all();
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('auth.member-register', compact('trainingPackages', 'bankAccounts'));
    }

    /**
     * Menangani permintaan registrasi member.
     */
    public function store(Request $request)
    {
        // 1. Validasi input, termasuk bukti pembayaran
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female'],
            'training_package_id' => ['required', 'exists:training_packages,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'proof_of_payment' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // 2. Generate email unik
        $baseEmail = Str::slug($request->name, '.');
        $domain = '@cikampekswimming.club';
        $email = $baseEmail . $domain;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . $counter . $domain;
            $counter++;
        }

        // Tambahkan ke request untuk validasi
        $request->merge(['email' => $email]);
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        // 3. Ambil data paket latihan
        $package = TrainingPackage::findOrFail($request->training_package_id);

        try {
            DB::transaction(function () use ($request, $email, $package) {
                // Upload bukti pembayaran
                $proofPath = $request->file('proof_of_payment')->store('payment-proofs', 'public');

                // Buat user baru
                $user = User::create([
                    'name' => $request->name,
                    'email' => $email,
                    'phone' => $request->phone,
                    'birth_date' => $request->birth_date,
                    'gender' => strtoupper($request->gender),
                    'password' => Hash::make($request->password),
                    'active' => false,
                ]);

                $user->assignRole('member');

                // Buat data member
                $member = Member::create([
                    'user_id' => $user->id,
                    'training_package_id' => $request->training_package_id,
                    'status' => 'AKTIF',
                    'start_date' => Carbon::now(),
                    'end_date' => Carbon::now()->addMonth(),
                ]);

                // Buat histori pembayaran
                $paymentHistory = PaymentHistory::create([
                    'member_id' => $member->id,
                    'amount' => $package->price,
                    'description' => 'Pembayaran Pendaftaran Paket: ' . $package->name,
                    'payment_date' => Carbon::now(),
                    'status' => 'PENDING',
                    'proof_path' => $proofPath,
                ]);

                // Ambil semua admin/staff
                $admins = User::role('staff')->get();

                foreach ($admins as $admin) {
                    // Kirim notifikasi ke setiap admin
                    $admin->notify(new NewMemberRegistered($user, $paymentHistory));
                }
            });

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi. Error: ' . $e->getMessage());
        }

        // 5. Redirect dengan pesan sukses
        return redirect()
            ->route('member.register.store')
            ->with('status', 'Pendaftaran Berhasil! Akun Anda sedang menunggu konfirmasi pembayaran oleh Admin.');
    }
}
