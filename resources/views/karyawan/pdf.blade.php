<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $karyawan->nama }}</title>
    <style>
        @page {
            margin: 20px 30px;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #222;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #032b30;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #032b30;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .doc-title {
            font-size: 15px;
            font-weight: bold;
            margin: 5px 0 2px 0;
            color: #111;
        }
        .doc-period {
            font-size: 11px;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-label {
            width: 15%;
            font-weight: bold;
            color: #333;
        }
        .info-separator {
            width: 2%;
            text-align: center;
        }
        .info-value {
            width: 33%;
            color: #111;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th {
            background-color: #c5dc9e;
            color: #2e4708;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            padding: 8px 10px;
            border: 1px solid #a4c274;
            text-transform: uppercase;
        }
        .salary-table td {
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            vertical-align: top;
        }
        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sub-table td {
            border: none;
            padding: 6px 0;
        }
        .sub-table .text-end {
            text-align: right;
            font-weight: 600;
        }
        .sub-total-row td {
            border-top: 1px dashed #bbb;
            font-weight: bold;
            padding-top: 8px;
            margin-top: 4px;
        }
        .net-salary-box {
            background-color: #f0fdf4;
            border: 1.5px solid #86efac;
            padding: 12px 16px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .net-salary-title {
            font-size: 13px;
            font-weight: bold;
            color: #166534;
            display: inline-block;
            width: 40%;
        }
        .net-salary-val {
            font-size: 16px;
            font-weight: bold;
            color: #15803d;
            display: inline-block;
            width: 58%;
            text-align: right;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sig-space {
            height: 60px;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .footer-note {
            margin-top: 30px;
            font-size: 10px;
            color: #888;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <!-- Header / Kop -->
    <div class="header">
        <div class="doc-title" style="font-size: 18px; text-transform: uppercase;">SLIP GAJI KARYAWAN</div>
        <div class="doc-period">
            @php
                $tanggalAwal = $karyawan->tanggal_awal ? \Carbon\Carbon::parse($karyawan->tanggal_awal) : null;
                $tanggalAkhir = $karyawan->tanggal_akhir ? \Carbon\Carbon::parse($karyawan->tanggal_akhir) : null;
            @endphp
            PERIODE:
            @if ($tanggalAwal && $tanggalAkhir)
                {{ strtoupper($tanggalAwal->translatedFormat('d F Y')) }} - {{ strtoupper($tanggalAkhir->translatedFormat('d F Y')) }}
            @elseif ($tanggalAwal)
                {{ strtoupper($tanggalAwal->translatedFormat('d F Y')) }}
            @else
                -
            @endif
        </div>
    </div>

    <!-- Info Karyawan -->
    <table class="info-table">
        <tr>
            <td class="info-label">NIK</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $karyawan->nama }}</td>
            <td class="info-label">Jabatan</td>
            <td class="info-separator">:</td>
            <td class="info-value">{{ $karyawan->jabatan }}</td>
        </tr>
    </table>

    <!-- Rincian Gaji -->
    @php
        $gajiPokok = $karyawan->gaji_pokok ?? 0;
        $lembur = $karyawan->lembur ?? 0;
        $pinjaman = $karyawan->pinjaman ?? 0;
        $totalPenghasilan = $gajiPokok + $lembur;
        $totalPotongan = $pinjaman;
        $gajiBersih = $karyawan->gaji_bersih ?? ($totalPenghasilan - $totalPotongan);
    @endphp

    <table class="salary-table">
        <thead>
            <tr>
                <th style="width: 50%;">PENGHASILAN</th>
                <th style="width: 50%;">POTONGAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <!-- Kolom Penghasilan -->
                <td>
                    <table class="sub-table">
                        <tr>
                            <td>Gaji Pokok</td>
                            <td class="text-end">Rp {{ number_format($gajiPokok, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Uang Lembur</td>
                            <td class="text-end">Rp {{ number_format($lembur, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="sub-total-row">
                            <td>Total Penghasilan</td>
                            <td class="text-end">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>

                <!-- Kolom Potongan -->
                <td>
                    <table class="sub-table">
                        <tr>
                            <td>Pinjaman Karyawan</td>
                            <td class="text-end" style="color: #b91c1c;">Rp {{ number_format($pinjaman, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 18px;"></td>
                        </tr>
                        <tr class="sub-total-row">
                            <td>Total Potongan</td>
                            <td class="text-end" style="color: #b91c1c;">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Total Gaji Bersih -->
    <div class="net-salary-box">
        <span class="net-salary-title">TOTAL GAJI BERSIH (TAKE HOME PAY)</span>
        <span class="net-salary-val">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</span>
    </div>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                <div>Penerima,</div>
                <div class="sig-space"></div>
                <div class="sig-name">{{ $karyawan->nama }}</div>
                <div style="font-size: 11px; color: #666;">Karyawan</div>
            </td>
            <td>
                <div>Mengetahui,</div>
                <div class="sig-space"></div>
                <div class="sig-name">HRD / Keuangan</div>
                <div style="font-size: 11px; color: #666;">Manajemen</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini merupakan bukti pembayaran resmi yang sah dan dicetak secara otomatis melalui {{ config('app.name', 'Slip Gaji') }}.
    </div>

</body>
</html>
