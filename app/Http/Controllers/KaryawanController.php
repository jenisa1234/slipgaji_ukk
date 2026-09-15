<?php

namespace App\Http\Controllers;

use App\Mail\SlipGajiMail;
use App\Models\Karyawan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    /**
     * Tampilkan daftar karyawan
     */
    public function index()
    {
        $karyawan = Karyawan::orderBy('id', 'desc')->get();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Tampilkan form tambah karyawan
     */
    public function create()
    {
        $captcha = $this->createCaptcha(request());
        $periodeBulan = old('periode_bulan', now()->month);
        $periodeTahun = old('periode_tahun', now()->year);

        return view('karyawan.create', compact('captcha', 'periodeBulan', 'periodeTahun'));
    }

    /**
     * Simpan data karyawan baru
     */
    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nik'           => 'required|string|max:20|unique:karyawans,nik',
            'nama'          => 'required|string|max:100',
            'jabatan'       => 'required|string|max:50',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|between:2000,2100',
            'gaji_pokok'    => 'required|numeric|min:0',
            'lembur'        => 'nullable|numeric|min:0',
            'pinjaman'      => 'nullable|numeric|min:0',
            'captcha_input' => ['required', 'integer', function ($attribute, $value, $fail) use ($request) {
                if ((int) $value !== (int) $request->session()->get('karyawan_captcha_answer')) {
                    $fail('Jawaban captcha salah.');
                }
            }],
        ], [
            'nik.unique'       => 'NIK sudah terdaftar! Harap gunakan NIK lain.',
            'nik.required'     => 'NIK wajib diisi.',
            'nama.required'    => 'Nama karyawan wajib diisi.',
            'jabatan.required' => 'Jabatan karyawan wajib diisi.',
            'periode_bulan.required' => 'Bulan periode wajib dipilih.',
            'periode_tahun.required' => 'Tahun periode wajib dipilih.',
        ]);

        // 2. Olah Nilai
        $gajiPokok  = (float) $request->input('gaji_pokok', 0);
        $lembur     = (float) $request->input('lembur', 0);
        $pinjaman   = (float) $request->input('pinjaman', 0);
        $gajiBersih = ($gajiPokok + $lembur) - $pinjaman;
        $periode = $this->hitungPeriode((int) $request->input('periode_bulan'), (int) $request->input('periode_tahun'));

        // 3. Simpan ke Database
        Karyawan::create([
            'nik'         => $request->nik,
            'nama'        => $request->nama,
            'jabatan'     => $request->jabatan,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'tanggal_awal' => $periode['tanggal_awal'],
            'tanggal_akhir' => $periode['tanggal_akhir'],
            'gaji_pokok'  => $gajiPokok,
            'lembur'      => $lembur,
            'pinjaman'    => $pinjaman,
            'gaji_bersih' => $gajiBersih,
        ]);

        $request->session()->forget(['karyawan_captcha_answer', 'karyawan_captcha_question']);

        return redirect()->route('karyawan.index')->with('success', str_replace(':name', $request->nama, config('ui.messages.success_create', 'Data karyawan berhasil ditambahkan!')));
    }

    /**
     * Tampilkan form edit karyawan
     */
    public function edit($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $captcha = $this->createCaptcha(request());
        $periodeBulan = old('periode_bulan', $karyawan->periode_bulan ?? ($karyawan->tanggal_akhir ? Carbon::parse($karyawan->tanggal_akhir)->month : now()->month));
        $periodeTahun = old('periode_tahun', $karyawan->periode_tahun ?? ($karyawan->tanggal_akhir ? Carbon::parse($karyawan->tanggal_akhir)->year : now()->year));

        return view('karyawan.edit', compact('karyawan', 'captcha', 'periodeBulan', 'periodeTahun'));
    }

    /**
     * Update data karyawan
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'nik'           => ['required', 'string', 'max:20', Rule::unique('karyawans')->ignore($karyawan->id)],
            'nama'          => 'required|string|max:100',
            'jabatan'       => 'required|string|max:50',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer|between:2000,2100',
            'gaji_pokok'    => 'required|numeric|min:0',
            'lembur'        => 'nullable|numeric|min:0',
            'pinjaman'      => 'nullable|numeric|min:0',
            'captcha_input' => ['required', 'integer', function ($attribute, $value, $fail) use ($request) {
                if ((int) $value !== (int) $request->session()->get('karyawan_captcha_answer')) {
                    $fail('Jawaban captcha salah.');
                }
            }],
        ], [
            'nik.unique'       => 'NIK sudah digunakan oleh karyawan lain!',
            'nik.required'     => 'NIK wajib diisi.',
            'nama.required'    => 'Nama karyawan wajib diisi.',
            'jabatan.required' => 'Jabatan wajib diisi.',
            'periode_bulan.required' => 'Bulan periode wajib dipilih.',
            'periode_tahun.required' => 'Tahun periode wajib dipilih.',
        ]);

        $gajiPokok  = (float) $request->input('gaji_pokok', 0);
        $lembur     = (float) $request->input('lembur', 0);
        $pinjaman   = (float) $request->input('pinjaman', 0);
        $gajiBersih = ($gajiPokok + $lembur) - $pinjaman;
        $periode = $this->hitungPeriode((int) $request->input('periode_bulan'), (int) $request->input('periode_tahun'));

        $karyawan->update([
            'nik'         => $request->nik,
            'nama'        => $request->nama,
            'jabatan'     => $request->jabatan,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
            'tanggal_awal' => $periode['tanggal_awal'],
            'tanggal_akhir' => $periode['tanggal_akhir'],
            'gaji_pokok'  => $gajiPokok,
            'lembur'      => $lembur,
            'pinjaman'    => $pinjaman,
            'gaji_bersih' => $gajiBersih,
        ]);

        $request->session()->forget(['karyawan_captcha_answer', 'karyawan_captcha_question']);

        return redirect()->route('karyawan.index')->with('success', str_replace(':name', $karyawan->nama, config('ui.messages.success_update', 'Data karyawan :name berhasil diperbarui!')));
    }

    private function createCaptcha(Request $request): string
    {
        $left = random_int(2, 9);
        $right = random_int(2, 9);
        $question = $left . '*' . $right;

        $request->session()->put([
            'karyawan_captcha_question' => $question,
            'karyawan_captcha_answer'   => $left * $right,
        ]);

        return $question;
    }

    private function hitungPeriode(int $bulan, int $tahun): array
    {
        $tanggalGajian = max(1, min((int) config('ui.payday_day', 25), 31));
        $tanggalAkhir = Carbon::create($tahun, $bulan, 1)->setDay(min($tanggalGajian, Carbon::create($tahun, $bulan, 1)->daysInMonth));
        $tanggalAwal = $tanggalAkhir->copy()->subMonthNoOverflow();

        return [
            'tanggal_awal' => $tanggalAwal->toDateString(),
            'tanggal_akhir' => $tanggalAkhir->toDateString(),
        ];
    }

    public function refreshCaptcha(Request $request)
    {
        return response()->json([
            'question' => $this->createCaptcha($request),
        ]);
    }

    /**
     * Hapus data karyawan
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $nama = $karyawan->nama;
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', str_replace(':name', $nama, config('ui.messages.success_delete', 'Data karyawan :name berhasil dihapus!')));
    }

    /**
     * Tampilkan detail Slip Gaji Karyawan
     */
    public function slip($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        return view('karyawan.slip', compact('karyawan'));
    }

    /**
     * Cetak dokumen PDF Slip Gaji
     */
    public function cetakSlip($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $pdf = Pdf::loadView('karyawan.pdf', compact('karyawan'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Slip_Gaji_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $karyawan->nama) . '.pdf';

        return $pdf->stream($fileName);
    }

    /**
     * Kirim Slip Gaji via Email
     */
    public function kirimEmail(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $tujuanEmail = $request->input('email');

        try {
            // Buat PDF untuk dilampirkan ke email
            $pdf = Pdf::loadView('karyawan.pdf', compact('karyawan'))
                ->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();

            // Kirim Email
            Mail::to($tujuanEmail)->send(new SlipGajiMail($karyawan, $pdfContent));

            // Jika karyawan belum memiliki email tersimpan di database, simpan email ini
            if (empty($karyawan->email)) {
                $karyawan->update(['email' => $tujuanEmail]);
            }

            return redirect()->back()->with('success', str_replace(':email', $tujuanEmail, config('ui.messages.success_email', 'Slip gaji berhasil dikirimkan ke email: :email')));
        } catch (\Exception $e) {
            Log::error('Error pengiriman email slip gaji: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}