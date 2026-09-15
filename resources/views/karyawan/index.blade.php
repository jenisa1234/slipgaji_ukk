@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark m-0">{{ config('ui.page.dashboard', 'Data Karyawan') }}</h3>
        <p class="text-muted small m-0">Kelola informasi karyawan dan perhitungan gaji</p>
    </div>
    <a href="{{ route('karyawan.create') }}" class="btn text-white fw-semibold px-3 py-2" style="background-color: #032b30;">
        <i class="bi bi-person-plus-fill me-1"></i> {{ config('ui.button.add', 'Tambah Karyawan') }}
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle m-0">
                <thead class="bg-light">
                    <tr class="text-uppercase small fw-bold text-dark border-bottom">
                        <th class="py-3 px-3 text-center" style="width: 50px;">NO</th>
                        <th class="py-3 px-3">NIK</th>
                        <th class="py-3 px-3">NAMA</th>
                        <th class="py-3 px-3">JABATAN</th>
                        <th class="py-3 px-3">PERIODE</th>
                        <th class="py-3 px-3">GAJI POKOK</th>
                        <th class="py-3 px-3">LEMBUR</th>
                        <th class="py-3 px-3">PINJAMAN</th>
                        <th class="py-3 px-3">GAJI BERSIH</th>
                        <th class="py-3 px-3 text-center" style="width: 220px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawan ?? [] as $index => $item)
                        <tr>
                            <td class="text-center py-3 px-3">{{ $index + 1 }}</td>
                            <td class="py-3 px-3 fw-medium">{{ $item->nik }}</td>
                            <td class="py-3 px-3 fw-semibold text-dark">{{ $item->nama }}</td>
                            <td class="py-3 px-3">{{ $item->jabatan }}</td>
                            <td class="py-3 px-3">
                                @if ($item->tanggal_awal && $item->tanggal_akhir)
                                    {{ \Carbon\Carbon::parse($item->tanggal_awal)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($item->tanggal_akhir)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-3">Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="py-3 px-3">Rp {{ number_format($item->lembur ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-danger">Rp {{ number_format($item->pinjaman ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3 px-3 fw-bold text-success">Rp {{ number_format($item->gaji_bersih, 0, ',', '.') }}</td>
                            <td class="text-center py-3 px-3">
                                <div class="btn-group" role="group">
                                    <!-- Lihat / Cetak Slip -->
                                    <a href="{{ route('karyawan.slip', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat & Cetak Slip">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </a>

                                    <!-- Share WhatsApp (Modal Pop-up) -->
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-success" 
                                        title="Share WhatsApp"
                                        onclick="openWaModal('{{ $item->nama }}', '{{ $item->nik }}', '{{ $item->jabatan }}', '{{ number_format($item->gaji_pokok, 0, ',', '.') }}', '{{ number_format($item->lembur ?? 0, 0, ',', '.') }}', '{{ number_format($item->pinjaman ?? 0, 0, ',', '.') }}', '{{ number_format($item->gaji_bersih, 0, ',', '.') }}', '{{ $item->tanggal_awal ? \Carbon\Carbon::parse($item->tanggal_awal)->format('d/m/Y') : '-' }}', '{{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d/m/Y') : '-' }}')">
                                        <i class="bi bi-whatsapp"></i>
                                    </button>

                                    <!-- Share Email (Modal Pop-up) -->
                                    <button type="button" 
                                        class="btn btn-sm btn-outline-info" 
                                        title="Share Email"
                                        onclick="openEmailModal('{{ $item->nama }}', '{{ $item->nik }}', '{{ $item->jabatan }}', '{{ number_format($item->gaji_pokok, 0, ',', '.') }}', '{{ number_format($item->lembur ?? 0, 0, ',', '.') }}', '{{ number_format($item->pinjaman ?? 0, 0, ',', '.') }}', '{{ number_format($item->gaji_bersih, 0, ',', '.') }}', '{{ $item->tanggal_awal ? \Carbon\Carbon::parse($item->tanggal_awal)->format('d/m/Y') : '-' }}', '{{ $item->tanggal_akhir ? \Carbon\Carbon::parse($item->tanggal_akhir)->format('d/m/Y') : '-' }}')">
                                        <i class="bi bi-envelope"></i>
                                    </button>

                                    <!-- Edit -->
                                    <a href="{{ route('karyawan.edit', $item->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Karyawan">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <!-- Hapus -->
                                    <form action="{{ route('karyawan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ str_replace(':name', $item->nama, config('ui.messages.confirm_delete', 'Yakin ingin menghapus data karyawan :name?')) }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus Data">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    <span>{{ config('ui.messages.empty_table', 'Belum ada data karyawan. Sila tambahkan data baru.') }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL POPUP SHARE WHATSAPP (INDEX) -->
<div class="modal fade" id="indexWaModal" tabindex="-1" aria-labelledby="indexWaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="indexWaModalLabel">
                    <i class="bi bi-whatsapp me-2"></i> Share Slip Gaji via WhatsApp
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Masukkan nomor WhatsApp tujuan. Teks rincian slip gaji resmi akan langsung disiapkan saat WhatsApp terbuka.
                </p>
                <div class="mb-3">
                    <label for="modal_wa_nomor" class="form-label fw-bold">Nomor WhatsApp Penerima</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="text" id="modal_wa_nomor" class="form-control" placeholder="Contoh: 08123456789 atau 628123456789">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small text-muted">{{ config('ui.messages.wa_preview_label', 'Preview Pesan Slip Gaji:') }}</label>
                    <div class="p-2 bg-light border rounded small" style="white-space: pre-wrap; font-family: monospace;" id="modal_wa_preview"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" onclick="kirimModalWhatsApp()" class="btn btn-success fw-semibold">
                    <i class="bi bi-send-fill me-1"></i> Buka WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP SHARE EMAIL (INDEX) -->
<div class="modal fade" id="indexEmailModal" tabindex="-1" aria-labelledby="indexEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="indexEmailModalLabel">
                    <i class="bi bi-envelope-fill me-2"></i> Kirim Slip Gaji via Gmail
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Masukkan alamat email karyawan. Sistem akan langsung membuka Gmail dengan rincian slip gaji yang sudah terisi otomatis.
                </p>
                <div class="mb-3">
                    <label for="modal_email_input" class="form-label fw-bold">Alamat Email Penerima</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="email" id="modal_email_input" class="form-control" placeholder="karyawan@example.com" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold small text-muted">{{ config('ui.messages.email_preview_label', 'Preview Isi Email:') }}</label>
                    <div class="p-2 bg-light border rounded small" style="white-space: pre-wrap; font-family: monospace;" id="modal_email_preview"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" onclick="kirimModalEmail()" class="btn btn-primary fw-semibold">
                    <i class="bi bi-send-fill me-1"></i> Buka & Kirim via Gmail
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentWaPesan = '';
    let currentEmailNama = '';
    let currentEmailPesan = '';

    function openWaModal(nama, nik, jabatan, gajiPokok, lembur, pinjaman, gajiBersih, tanggalAwal, tanggalAkhir) {
        document.getElementById('modal_wa_nomor').value = '';
        
        const periode = tanggalAwal !== '-' && tanggalAkhir !== '-'
            ? `${tanggalAwal} - ${tanggalAkhir}`
            : 'Belum diatur';
        currentWaPesan = `*SLIP GAJI KARYAWAN*
Periode: ${periode}

Nama: ${nama}
NIK: ${nik}
Jabatan: ${jabatan}
-----------------------------
Gaji Pokok: Rp ${gajiPokok}
Lembur: Rp ${lembur}
Pinjaman: Rp ${pinjaman}
-----------------------------
*Total Gaji Bersih: Rp ${gajiBersih}*

Terima kasih atas dedikasi dan kerja keras Anda!`;

        document.getElementById('modal_wa_preview').innerText = currentWaPesan;
        const modal = new bootstrap.Modal(document.getElementById('indexWaModal'));
        modal.show();
    }

    function kirimModalWhatsApp() {
        let nomor = document.getElementById('modal_wa_nomor').value.trim();
        if (!nomor) {
            alert('Silakan masukkan nomor WhatsApp karyawan terlebih dahulu!');
            return;
        }

        nomor = nomor.replace(/[^0-9]/g, '');
        if (nomor.startsWith('0')) {
            nomor = '62' + nomor.slice(1);
        }

        const url = `https://wa.me/${nomor}?text=${encodeURIComponent(currentWaPesan)}`;
        window.open(url, '_blank');
    }

    function openEmailModal(nama, nik, jabatan, gajiPokok, lembur, pinjaman, gajiBersih, tanggalAwal, tanggalAkhir) {
        currentEmailNama = nama;
        document.getElementById('modal_email_input').value = '';
        
        const periode = tanggalAwal !== '-' && tanggalAkhir !== '-'
            ? `${tanggalAwal} - ${tanggalAkhir}`
            : 'Belum diatur';
        currentEmailPesan = `Halo ${nama},

Berikut rincian slip gaji Anda untuk periode ${periode}:

Nama: ${nama}
NIK: ${nik}
Jabatan: ${jabatan}
------------------------------------
Gaji Pokok: Rp ${gajiPokok}
Uang Lembur: Rp ${lembur}
Potongan Pinjaman: Rp ${pinjaman}
------------------------------------
TOTAL GAJI BERSIH: Rp ${gajiBersih}

Terima kasih atas dedikasi dan kerja keras Anda!

Salam,
HRD / Keuangan`;

        document.getElementById('modal_email_preview').innerText = currentEmailPesan;
        const modal = new bootstrap.Modal(document.getElementById('indexEmailModal'));
        modal.show();
    }

    function kirimModalEmail() {
        const email = document.getElementById('modal_email_input').value.trim();
        if (!email) {
            alert('Silakan masukkan alamat email karyawan terlebih dahulu!');
            return;
        }

        const subject = `SLIP GAJI KARYAWAN - ${currentEmailNama}`;
        const body = currentEmailPesan;
        const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        window.open(gmailUrl, '_blank');
    }
</script>
@endpush
@endsection
