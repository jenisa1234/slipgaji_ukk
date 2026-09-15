@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
@php
    $formSiap = old('periode_bulan') || $errors->any();
    $namaBulan = [1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MEI', 6 => 'JUN', 7 => 'JUL', 8 => 'AGU', 9 => 'SEP', 10 => 'OKT', 11 => 'NOV', 12 => 'DES'];
@endphp

<div id="form-karyawan" class="card border-0 shadow-sm rounded-3 bg-white p-4" @if (!$formSiap) hidden @endif>
    <!-- Header Slip -->
    <div class="text-center mb-4">
        <h4 class="fw-bold m-0 text-dark">{{ strtoupper(config('ui.page.slip', 'SLIP GAJI KARYAWAN')) }}</h4>
        <div class="mt-2 fw-bold text-primary" id="periode-terpilih">
            PERIODE {{ $namaBulan[(int) $periodeBulan] ?? 'JAN' }}
        </div>
        <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 mt-1" id="ubah-periode">Ubah Periode</button>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger py-2 px-3 mb-3">
            <ul class="m-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('karyawan.store') }}" method="POST">
        @csrf

        <!-- Data Utama Karyawan -->
        <div class="form-karyawan-group mb-4">
            <div class="row align-items-center mb-2">
                <label for="nama" class="col-sm-2 col-form-label fw-bold text-dark">NAMA</label>
                <div class="col-sm-10">
                    <input type="text" name="nama" id="nama" class="form-control border-orange" value="{{ old('nama') }}" required>
                </div>
            </div>

            <div class="row align-items-center mb-2">
                <label for="nik" class="col-sm-2 col-form-label fw-bold text-dark">NIK</label>
                <div class="col-sm-10">
                    <input type="text" name="nik" id="nik" class="form-control border-orange" value="{{ old('nik') }}" required>
                </div>
            </div>

            <div class="row align-items-center mb-2">
                <label for="jabatan" class="col-sm-2 col-form-label fw-bold text-dark">JABATAN</label>
                <div class="col-sm-10">
                    <input type="text" name="jabatan" id="jabatan" class="form-control border-orange" value="{{ old('jabatan') }}" required>
                </div>
            </div>

            <input type="hidden" name="periode_bulan" id="periode_bulan" value="{{ $periodeBulan }}" required>
            <input type="hidden" name="periode_tahun" id="periode_tahun" value="{{ $periodeTahun }}" required>
        </div>

        <!-- Section Header Penghasilan & Potongan -->
        <div class="row g-0 text-center fw-bold py-2 mb-3 rounded-1" style="background-color: #c5dc9e; color: #2e4708;">
            <div class="col-6">PENGHASILAN</div>
            <div class="col-6">POTONGAN</div>
        </div>

        <!-- Section Detail Gaji & Potongan -->
        <div class="row mb-3">
            <!-- Left: Penghasilan -->
            <div class="col-md-6 pe-md-4 border-end">
                <div class="row align-items-center mb-3">
                    <label for="gaji_pokok" class="col-sm-4 col-form-label text-secondary fw-medium">Gaji Pokok</label>
                    <div class="col-sm-8">
                        <input type="number" name="gaji_pokok" id="gaji_pokok" class="form-control border-orange hitung-gaji" value="{{ old('gaji_pokok', 0) }}" required min="0">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="lembur" class="col-sm-4 col-form-label text-secondary fw-medium">Lembur</label>
                    <div class="col-sm-8">
                        <input type="number" name="lembur" id="lembur" class="form-control border-orange hitung-gaji" value="{{ old('lembur', 0) }}" min="0">
                    </div>
                </div>
                <div class="row align-items-center pt-2">
                    <label class="col-sm-4 col-form-label fw-bold text-dark">Total Penghasilan</label>
                    <div class="col-sm-8">
                        <input type="number" id="total_penghasilan" class="form-control border-orange bg-light" readonly value="0">
                    </div>
                </div>
            </div>

            <!-- Right: Potongan -->
            <div class="col-md-6 ps-md-4 d-flex flex-column justify-content-between">
                <div class="row align-items-center mb-3">
                    <label for="pinjaman" class="col-sm-4 col-form-label text-secondary fw-medium">Pinjaman Karyawan</label>
                    <div class="col-sm-8">
                        <input type="number" name="pinjaman" id="pinjaman" class="form-control border-orange hitung-gaji" value="{{ old('pinjaman', 0) }}" min="0">
                    </div>
                </div>
                <div class="row align-items-center pt-2">
                    <label class="col-sm-4 col-form-label fw-bold text-dark">Total Potongan</label>
                    <div class="col-sm-8">
                        <input type="number" id="total_potongan" class="form-control border-orange bg-light" readonly value="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Gaji Bersih -->
        <div class="row align-items-center py-2 px-3 mb-4 rounded-1" style="background-color: #c5dc9e;">
            <div class="col-sm-3 fw-bold text-dark">Gaji Bersih</div>
            <div class="col-sm-9">
                <input type="number" name="gaji_bersih" id="gaji_bersih" class="form-control border-orange bg-white fw-bold text-success fs-5" readonly value="0">
            </div>
        </div>

        <!-- Captcha & Actions -->
        <div class="row align-items-end">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-secondary px-3 py-2 fs-6">Captcha : <span id="captcha-question">{{ $captcha }}</span></span>
                    <button class="btn btn-sm btn-outline-secondary" type="button" id="refresh-captcha" title="Ganti captcha"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <input type="text" name="captcha_input" class="form-control border-orange @error('captcha_input') is-invalid @enderror" style="max-width: 250px;" placeholder="Masukkan hasil Captcha" required inputmode="numeric">
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary px-4 me-2">{{ config('ui.button.cancel', 'Batal') }}</a>
                <button type="submit" class="btn text-white px-4 fw-semibold" style="background-color: #4b85ce;">{{ config('ui.button.submit', 'Submit') }}</button>
            </div>
        </div>
    </form>
</div>

<div class="modal fade show d-block" id="periodeModal" tabindex="-1" aria-modal="true" role="dialog" @if ($formSiap) hidden @endif>
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Pilih Periode</h5>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Tentukan bulan dan tahun slip gaji. Tanggal gajian otomatis setiap tanggal {{ config('ui.payday_day', 25) }}.</p>
                <label for="pilih_bulan" class="form-label fw-semibold">Bulan</label>
                <select id="pilih_bulan" class="form-select border-orange mb-3">
                    @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $nomorBulan => $namaBulanPilihan)
                        <option value="{{ $nomorBulan }}" @selected((int) $periodeBulan === $nomorBulan)>{{ $namaBulanPilihan }}</option>
                    @endforeach
                </select>
                <label for="pilih_tahun" class="form-label fw-semibold">Tahun</label>
                <select id="pilih_tahun" class="form-select border-orange">
                    @for ($tahun = now()->year - 1; $tahun <= now()->year + 5; $tahun++)
                        <option value="{{ $tahun }}" @selected((int) $periodeTahun === $tahun)>{{ $tahun }}</option>
                    @endfor
                </select>
                <div class="alert alert-light border mt-3 mb-0 small">
                    Periode: <strong id="preview-periode"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="button" class="btn text-white" id="simpan-periode" style="background-color: #032b30;">Lanjut Isi Form</button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show" id="periodeBackdrop" @if ($formSiap) hidden @endif></div>

<style>
    #form-karyawan {
        position: relative;
        z-index: 1061;
    }

    .border-orange {
        border: 1.5px solid #ff8c32 !important;
        border-radius: 6px;
    }
    .border-orange:focus {
        border-color: #e06c14 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 140, 50, 0.25) !important;
    }
    #periodeModal .modal-content {
        position: relative;
        z-index: 1060;
    }
</style>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const gajiPokok = document.getElementById('gaji_pokok');
        const lembur = document.getElementById('lembur');
        const pinjaman = document.getElementById('pinjaman');

        const totalPenghasilan = document.getElementById('total_penghasilan');
        const totalPotongan = document.getElementById('total_potongan');
        const gajiBersih = document.getElementById('gaji_bersih');
        const periodeBulan = document.getElementById('periode_bulan');
        const periodeTahun = document.getElementById('periode_tahun');
        const pilihBulan = document.getElementById('pilih_bulan');
        const pilihTahun = document.getElementById('pilih_tahun');
        const periodeModal = document.getElementById('periodeModal');
        const periodeBackdrop = document.getElementById('periodeBackdrop');
        const formKaryawan = document.getElementById('form-karyawan');
        const namaBulan = @json($namaBulan);

        function formatPeriode() {
            const bulan = parseInt(pilihBulan.value, 10);
            const tahun = parseInt(pilihTahun.value, 10);
            const tanggalAkhir = new Date(tahun, bulan - 1, 25);
            const tanggalAwal = new Date(tahun, bulan - 2, 25);
            const teks = `25 ${namaBulan[tanggalAwal.getMonth() + 1]} - 25 ${namaBulan[tanggalAkhir.getMonth() + 1]} ${tanggalAkhir.getFullYear()}`;
            document.getElementById('preview-periode').textContent = teks;
            document.getElementById('periode-terpilih').textContent = `PERIODE ${teks}`;
        }

        function bukaPopupPeriode() {
            periodeModal.hidden = false;
            periodeModal.classList.add('show');
            periodeBackdrop.hidden = false;
            document.body.classList.add('modal-open');
        }

        function tutupPopupPeriode() {
            periodeModal.hidden = true;
            periodeModal.classList.remove('show');
            periodeBackdrop.hidden = true;
            document.body.classList.remove('modal-open');
            formKaryawan.hidden = false;
            formKaryawan.classList.remove('d-none');
            document.getElementById('nama').focus();
        }

        pilihBulan.value = periodeBulan.value;
        pilihTahun.value = periodeTahun.value;
        formatPeriode();
        pilihBulan.addEventListener('change', formatPeriode);
        pilihTahun.addEventListener('change', formatPeriode);
        document.getElementById('ubah-periode').addEventListener('click', bukaPopupPeriode);
        document.getElementById('simpan-periode').addEventListener('click', function () {
            periodeBulan.value = pilihBulan.value;
            periodeTahun.value = pilihTahun.value;
            formatPeriode();
            tutupPopupPeriode();
        });

        function hitung() {
            const valGaji = parseFloat(gajiPokok.value) || 0;
            const valLembur = parseFloat(lembur.value) || 0;
            const valPinjaman = parseFloat(pinjaman.value) || 0;

            const penghasilan = valGaji + valLembur;
            const potongan = valPinjaman;
            const bersih = penghasilan - potongan;

            totalPenghasilan.value = penghasilan;
            totalPotongan.value = potongan;
            gajiBersih.value = bersih;
        }

        document.querySelectorAll('.hitung-gaji').forEach(input => {
            input.addEventListener('input', hitung);
        });

        document.getElementById('refresh-captcha').addEventListener('click', async function () {
            const response = await fetch('{{ route('karyawan.captcha') }}');
            const data = await response.json();
            document.getElementById('captcha-question').textContent = data.question;
            document.querySelector('[name="captcha_input"]').value = '';
            document.querySelector('[name="captcha_input"]').focus();
        });

        hitung();
    });
</script>
@endpush
@endsection