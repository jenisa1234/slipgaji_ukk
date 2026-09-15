@extends('layouts.app')

@section('title', 'Slip Gaji - ' . $karyawan->nama)

@section('content')
@php
    $tanggalAwal = $karyawan->tanggal_awal ? \Carbon\Carbon::parse($karyawan->tanggal_awal) : null;
    $tanggalAkhir = $karyawan->tanggal_akhir ? \Carbon\Carbon::parse($karyawan->tanggal_akhir) : null;
    $periode = $tanggalAwal && $tanggalAkhir
        ? strtoupper($tanggalAwal->translatedFormat('d F Y')) . ' - ' . strtoupper($tanggalAkhir->translatedFormat('d F Y'))
        : ($tanggalAwal ? strtoupper($tanggalAwal->translatedFormat('d F Y')) : '-');
@endphp
<div class="d-print-none mb-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <div>
        <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <!-- Tombol Cetak Browser -->
        <button type="button" onclick="window.print()" class="btn btn-outline-dark btn-sm fw-semibold">
            <i class="bi bi-printer me-1"></i> Print Langsung
        </button>

        <!-- Tombol Download / Cetak PDF -->
        <a href="{{ route('karyawan.slip.cetak', $karyawan->id) }}" target="_blank" class="btn btn-danger btn-sm fw-semibold">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak PDF
        </a>

        <!-- Tombol Modal WhatsApp -->
        <button type="button" class="btn btn-success btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#whatsappModal">
            <i class="bi bi-whatsapp me-1"></i> Share WhatsApp
        </button>

        <!-- Tombol Modal Email -->
        <button type="button" class="btn btn-primary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#emailModal">
            <i class="bi bi-envelope-fill me-1"></i> Share Email
        </button>
    </div>
</div>

<!-- Lembar Slip Gaji Card -->
<div class="card border-0 shadow-sm rounded-3 bg-white p-4 p-md-5 print-container" style="max-width: 850px; margin: 0 auto;">
    <!-- Kop Slip -->
    <div class="text-center pb-3 mb-4 border-bottom border-2" style="border-color: #032b30 !important;">
        <h3 class="fw-bold text-dark m-0">{{ strtoupper(config('ui.page.slip', 'Slip Gaji Karyawan')) }}</h3>
        <span class="badge bg-light text-dark border px-3 py-2 fw-semibold mt-2">
            PERIODE: {{ $periode }}
        </span>
    </div>

    <!-- Informasi Karyawan -->
    <div class="row g-3 mb-4 fs-6">
        <div class="col-md-6">
            <table class="table table-borderless table-sm m-0">
                <tr>
                    <td class="text-muted fw-semibold" style="width: 130px;">Nama Karyawan</td>
                    <td style="width: 15px;">:</td>
                    <td class="fw-bold text-dark">{{ $karyawan->nama }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">NIK</td>
                    <td>:</td>
                    <td class="fw-medium">{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <td class="text-muted fw-semibold">Jabatan</td>
                    <td>:</td>
                    <td class="fw-medium">{{ $karyawan->jabatan }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-6"></div>
    </div>

    <!-- Section Header Penghasilan & Potongan -->
    <div class="row g-0 text-center fw-bold py-2 mb-3 rounded-1" style="background-color: #c5dc9e; color: #2e4708;">
        <div class="col-6 border-end border-success">PENGHASILAN</div>
        <div class="col-6">POTONGAN</div>
    </div>

    @php
        $gajiPokok = $karyawan->gaji_pokok ?? 0;
        $lembur = $karyawan->lembur ?? 0;
        $pinjaman = $karyawan->pinjaman ?? 0;
        $totalPenghasilan = $gajiPokok + $lembur;
        $totalPotongan = $pinjaman;
        $gajiBersih = $karyawan->gaji_bersih ?? ($totalPenghasilan - $totalPotongan);
    @endphp

    <!-- Detail Gaji -->
    <div class="row mb-4">
        <!-- Kolom Penghasilan -->
        <div class="col-md-6 pe-md-4 border-end">
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-secondary">Gaji Pokok</span>
                <span class="fw-semibold">Rp {{ number_format($gajiPokok, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-secondary">Uang Lembur</span>
                <span class="fw-semibold">Rp {{ number_format($lembur, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex justify-content-between py-2 mt-2 fw-bold text-dark border-top">
                <span>Total Penghasilan</span>
                <span>Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Kolom Potongan -->
        <div class="col-md-6 ps-md-4 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-secondary">Pinjaman Karyawan</span>
                    <span class="fw-semibold text-danger">Rp {{ number_format($pinjaman, 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="d-flex justify-content-between py-2 mt-2 fw-bold text-dark border-top">
                <span>Total Potongan</span>
                <span class="text-danger">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Banner Gaji Bersih -->
    <div class="p-3 mb-5 rounded-2 d-flex justify-content-between align-items-center" style="background-color: #ecfdf5; border: 1.5px solid #a7f3d0;">
        <span class="fw-bold text-dark fs-5">TOTAL GAJI BERSIH (TAKE HOME PAY)</span>
        <span class="fw-bold text-success fs-3">Rp {{ number_format($gajiBersih, 0, ',', '.') }}</span>
    </div>

</div>

<!-- MODAL POPUP SHARE WHATSAPP -->
<div class="modal fade d-print-none" id="whatsappModal" tabindex="-1" aria-labelledby="whatsappModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="whatsappModalLabel">
                    <i class="bi bi-whatsapp me-2"></i> {{ __('Kirim Slip Gaji via WhatsApp') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Masukkan atau pastikan nomor WhatsApp tujuan aktif. Rincian slip gaji resmi akan otomatis terformat saat WhatsApp terbuka.
                </p>
                <div class="mb-3">
                    <label for="wa_nomor" class="form-label fw-bold">Nomor WhatsApp Penerima</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" id="wa_nomor" class="form-control" value="" placeholder="Contoh: 08123456789 atau 628123456789">
                    </div>
                    <small class="text-muted">Bisa menggunakan awalan 08... atau 628...</small>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small text-muted">Preview Pesan:</label>
                    <div class="p-2 bg-light border rounded small" style="white-space: pre-wrap; font-family: monospace;" id="wa_preview">
*SLIP GAJI KARYAWAN*
Periode: {{ $periode }}

Nama: {{ $karyawan->nama }}
NIK: {{ $karyawan->nik }}
Jabatan: {{ $karyawan->jabatan }}
-----------------------------
Gaji Pokok: Rp {{ number_format($gajiPokok, 0, ',', '.') }}
Lembur: Rp {{ number_format($lembur, 0, ',', '.') }}
Pinjaman: Rp {{ number_format($pinjaman, 0, ',', '.') }}
-----------------------------
*Total Gaji Bersih: Rp {{ number_format($gajiBersih, 0, ',', '.') }}*

Terima kasih atas dedikasi dan kerja keras Anda!
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" onclick="kirimWhatsApp()" class="btn btn-success fw-semibold">
                    <i class="bi bi-send-fill me-1"></i> Buka & Kirim WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP SHARE EMAIL -->
<div class="modal fade d-print-none" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="emailModalLabel">
                    <i class="bi bi-envelope-fill me-2"></i> {{ __('Kirim Slip Gaji via Gmail') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Masukkan alamat email karyawan. Sistem akan langsung membuka Gmail dengan rincian slip gaji yang sudah terisi otomatis.
                </p>
                <div class="mb-3">
                    <label for="email_penerima" class="form-label fw-bold">Alamat Email Penerima</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="email" id="email_penerima" class="form-control" value="" placeholder="karyawan@example.com" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small text-muted">Preview Isi Email:</label>
                    <div class="p-2 bg-light border rounded small" style="white-space: pre-wrap; font-family: monospace;" id="email_preview">
Halo {{ $karyawan->nama }},

Berikut adalah rincian slip gaji Anda untuk periode {{ $periode }}:

Nama: {{ $karyawan->nama }}
NIK: {{ $karyawan->nik }}
Jabatan: {{ $karyawan->jabatan }}
------------------------------------
Gaji Pokok: Rp {{ number_format($gajiPokok, 0, ',', '.') }}
Uang Lembur: Rp {{ number_format($lembur, 0, ',', '.') }}
Potongan Pinjaman: Rp {{ number_format($pinjaman, 0, ',', '.') }}
------------------------------------
TOTAL GAJI BERSIH: Rp {{ number_format($gajiBersih, 0, ',', '.') }}

Terima kasih atas dedikasi dan kerja keras Anda!

Salam,
HRD / Keuangan
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" onclick="kirimEmailLangsung()" class="btn btn-primary fw-semibold">
                    <i class="bi bi-send-fill me-1"></i> Buka & Kirim via Gmail
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: #ffffff !important;
        }
        #sidebar, .top-navbar, .d-print-none {
            display: none !important;
        }
        #content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }
        .print-container {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
    }
</style>

@push('scripts')
<script>
    function kirimWhatsApp() {
        let nomor = document.getElementById('wa_nomor').value.trim();
        if (!nomor) {
            alert('Silakan masukkan nomor WhatsApp karyawan terlebih dahulu!');
            return;
        }

        // Normalisasi nomor HP: ubah format 08... menjadi 628...
        nomor = nomor.replace(/[^0-9]/g, '');
        if (nomor.startsWith('0')) {
            nomor = '62' + nomor.slice(1);
        }

        const pesan = document.getElementById('wa_preview').innerText.trim();
        const url = `https://wa.me/${nomor}?text=${encodeURIComponent(pesan)}`;
        window.open(url, '_blank');
    }

    function kirimEmailLangsung() {
        const email = document.getElementById('email_penerima').value.trim();
        if (!email) {
            alert('Silakan masukkan alamat email karyawan terlebih dahulu!');
            return;
        }

        const subject = "SLIP GAJI KARYAWAN - {{ $karyawan->nama }}";
        const body = document.getElementById('email_preview').innerText.trim();
        const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        window.open(gmailUrl, '_blank');
    }
</script>
@endpush
@endsection

