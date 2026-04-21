<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TrainingPackage;
use App\Models\BankAccount;

class MemberRegistrationController extends Controller
{
    /**
     * Menampilkan form registrasi untuk member.
     */
    public function create()
    {
        $trainingPackages = TrainingPackage::all();
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('auth.registration-new', compact('trainingPackages', 'bankAccounts'));
    }

    public function pendaftar()
    {
        return view('auth.pendaftar');
    }
}
