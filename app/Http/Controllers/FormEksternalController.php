<?php

namespace App\Http\Controllers;

use App\Models\FormEksternal;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormEksternalController extends Controller
{
    public function show($slug)
    {
        $form = FormEksternal::where('slug', $slug)->firstOrFail();

        // 🟣 Cek kalau form belum aktif
        if ($form->status !== 'ACTIVE') {
            return view('form-externals.inactive', compact('form'));
        }

        // ✅ Kalau aktif, tampilkan form
        return view('form-externals.show', compact('form'));
    }


    public function submit(Request $request, $slug)
    {
        $form = FormEksternal::where('slug', $slug)
            ->where('status', 'ACTIVE')
            ->firstOrFail();

        // 🧠 Buat aturan validasi dinamis
        $rules = [];

        foreach ($form->fields as $field) {
            $name = $field['name'];

            // Hapus [] di akhir name biar validasi Laravel ngerti
            $key = str_replace('[]', '', $name);

            if (!empty($field['required'])) {
                // Checkbox & Select Multiple harus array
                if (in_array($field['type'], ['checkbox', 'select_multiple'])) {
                    $rules[$key] = 'required|array|min:1';
                } 
                // File upload
                elseif ($field['type'] === 'file') {
                    $rules[$key] = 'required|file';
                } 
                // Email format
                elseif ($field['type'] === 'email') {
                    $rules[$key] = 'required|email';
                } 
                // Default (text, number, dll)
                else {
                    $rules[$key] = 'required';
                }
            } else {
                // Jika gak required tapi file/email ada format
                if ($field['type'] === 'file') {
                    $rules[$key] = 'nullable|file';
                } elseif ($field['type'] === 'email') {
                    $rules[$key] = 'nullable|email';
                }
            }
        }

        // ✅ Validasi request sesuai aturan
        $validated = $request->validate($rules);

        // 🧩 Proses data form sesuai field type
        $data = [];

        foreach ($form->fields as $field) {
            $name = $field['name'];
            $key = str_replace('[]', '', $name);

            if ($field['type'] === 'file' && $request->hasFile($key)) {
                $data[$key] = $request->file($key)->store('uploads/form_files', 'public');
            } 
            elseif (in_array($field['type'], ['checkbox', 'select_multiple'])) {
                $data[$key] = $request->input($key, []); // array of values
            } 
            else {
                $data[$key] = $request->input($key);
            }
        }

        // 💾 Simpan ke database
        FormSubmission::create([
            'form_id' => $form->id,
            'data' => $data,
        ]);

        return redirect()->back()->with('success', 'Form berhasil dikirim!');
    }

}
