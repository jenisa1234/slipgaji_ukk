<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $karyawan->nama }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; }
        .header { background-color: #111827; color: #ffffff; padding: 20px; text-align: center; }
        .header h2 { margin: 0; font-size: 20px; }
        .content { padding: 24px; color: #374151; font-size: 14px; line-height: 1.6; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
        .table td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        .table .label { color: #6b7280; width: 40%; }
        .table .val { font-weight: 600; }
        .total-box { background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 6px; font-size: 16px; font-weight: bold; margin-top: 10px; text-align: right; }
        .footer { background-color: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    @php
        $tanggalAwal = $karyawan->tanggal_awal ? \Carbon\Carbon::parse($karyawan->tanggal_awal) : null;
        $tanggalAkhir = $karyawan->tanggal_akhir ? \Carbon\Carbon::parse($karyawan->tanggal_akhir) : null;
        $periode = $tanggalAwal && $tanggalAkhir
            ? strtoupper($tanggalAwal->translatedFormat('d F Y')) . ' - ' . strtoupper($tanggalAkhir->translatedFormat('d F Y'))
            : ($tanggalAwal ? strtoupper($tanggalAwal->translatedFormat('d F Y')) : '-');
    @endphp
    <div class="container">
        <div class="header">
            <h2>SLIP GAJI KARYAWAN</h2>
            <p style="margin: 5px 0 0 0; font-size: 12px; color: #9ca3af;">Periode: {{ $periode }}</p>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $karyawan->nama }}</strong>,</p>
            <p>Berikut adalah rincian slip gaji Anda untuk periode tersebut:</p>

            <table class="table">
                <tr>
                    <td class="label">Nama Karyawan</td>
                    <td class="val">{{ $karyawan->nama }}</td>
                </tr>
                <tr>
                    <td class="label">NIK</td>
                    <td class="val">{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <td class="label">Jabatan</td>
                    <td class="val">{{ $karyawan->jabatan }}</td>
                </tr>
                <tr>
                    <td class="label">Gaji Pokok</td>
                    <td class="val">Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Uang Lembur</td>
                    <td class="val">Rp {{ number_format($karyawan->lembur ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Potongan Pinjaman</td>
                    <td class="val" style="color: #dc2626;">- Rp {{ number_format($karyawan->pinjaman ?? 0, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="total-box">
                Gaji Bersih: Rp {{ number_format($karyawan->gaji_bersih, 0, ',', '.') }}
            </div>

            <p style="margin-top: 20px; font-size: 13px; color: #6b7280;">
                * Lampiran dokumen PDF resmi slip gaji juga disertakan bersama email ini.
            </p>
        </div>
        <div class="footer">
            Email ini dikirim secara otomatis oleh Sistem Penggajian Karyawan.<br>
            Harap hubungi bagian HRD / Keuangan jika terdapat pertanyaan terkait slip ini.
        </div>
    </div>
</body>
</html>
