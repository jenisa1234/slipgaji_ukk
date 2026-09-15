@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="card border-0 shadow-sm rounded-3 bg-white p-4">
    <!-- Header Slip -->
    <div class="text-center mb-4">
        <h4 class="fw-bold m-0 text-dark">{{ strtoupper(config('ui.page.slip', 'SLIP GAJI KARYAWAN')) }}</h4>
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

            <div class="row align-items-center mb-2">
                <label for="periode_bulan" class="col-sm-2 col-form-label fw-bold text-dark">PERIODE GAJI</label>
                <div class="col-sm-4">
                    <select name="periode_bulan" id="periode_bulan" class="form-select border-orange" required>
                        <option value="">Pilih bulan</option>
                        @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $nomorBulan => $namaBulan)
                            <option value="{{ $nomorBulan }}" @selected((int) $periodeBulan === $nomorBulan)>{{ $namaBulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 small text-muted">Tanggal periode akan dihitung otomatis berdasarkan pengaturan tanggal gajian.</div>
            </div>
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

<style>
    .border-orange {
        border: 1.5px solid #ff8c32 !important;
        border-radius: 6px;
    }
    .border-orange:focus {
        border-color: #e06c14 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 140, 50, 0.25) !important;
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