@extends('layouts/app')

@section('content')
<div class="container-fluid">

    {{-- Judul Halaman --}}
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user mr-2"></i>
        {{ $title }}
    </h1>

    {{-- Filter Prodi & Semester --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @php
                $hasProdi     = request()->filled('prodi');
                $hasSemester  = request()->filled('semester');
                $hasKelompok  = request()->filled('kelompok');

                $isComplete = $hasProdi && $hasSemester && $hasKelompok;
            @endphp

            {{-- Filter Prodi --}}
            <h6 class="text-muted mb-2">
                <i class="fas fa-filter mr-1"></i>
                Filter Program Studi
            </h6>

            <div class="d-flex flex-wrap mb-4" style="gap: .5rem;">
                @php
                    $prodi = [
                        'PAI','PBA','TBI','PBA INTERNASIONAL','IQT','AFI','PM','HES','MB','EI','HI', 'ILKOM', 'TI', 'AGRO','GIZI','FARM'
                    ];
                @endphp

                @foreach ($prodi as $p)
                    <a href="?prodi={{ trim($p) }}&semester={{ trim(request('semester')) }}"
                       class="btn btn-sm btn-outline-primary {{ request('prodi') == $p ? 'active' : '' }}">
                        {{ $p }}
                    </a>
                @endforeach
            </div>

            {{-- Filter Semester --}}
            <h6 class="text-muted mb-2">
                <i class="fas fa-layer-group mr-1"></i>
                Filter Semester
            </h6>

            <div class="d-flex flex-wrap" style="gap: .5rem;">
                @for ($s = 1; $s <= 8; $s++)
                    <a href="?prodi={{ trim(request('prodi')) }}&semester={{ $s }}"
                       class="btn btn-sm btn-outline-success {{ request('semester') == $s ? 'active' : '' }}">
                        Semester {{ $s }}
                    </a>
                @endfor
            </div>

            {{-- Filter Kelompok --}}
            {{-- PERUBAHAN 1: Cek apakah list kelompok ada --}}
            @if(isset($kelompokList) && count($kelompokList) > 0)
                <h6 class="text-muted mt-4 mb-2">
                    <i class="fas fa-users mr-1"></i>
                    Filter Kelompok
                </h6>

                <div class="d-flex flex-wrap" style="gap: .5rem;">
                    {{-- PERUBAHAN 2: Looping Objek KelompokLT --}}
                    @foreach ($kelompokList as $k)
                        {{-- Gunakan id_kelompok untuk URL, kode_kelompok untuk Tampilan --}}
                        <a href="?prodi={{ trim(request('prodi')) }}
                            &semester={{ trim(request('semester')) }}
                            &kelompok={{ $k->id_kelompok }}"
                        class="btn btn-sm btn-outline-warning {{ request('kelompok') == $k->id_kelompok ? 'active' : '' }}">
                            Kelompok {{ $k->kode_kelompok }}
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- Konten Absensi --}}
    <div class="card shadow">
        <div class="card-body">

            @if($isComplete)
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">

                    {{-- KIRI: Judul + filter aktif --}}
                    <div class="d-flex align-items-center flex-wrap" style="gap:.5rem;">
                        <h5 class="mb-0 mr-2">Data Absensi</h5>

                        @if(request('prodi'))
                            <a href="?semester={{ request('semester') }}"
                            class="btn btn-sm btn-primary">
                                {{ request('prodi') }} <span class="ml-1">&times;</span>
                            </a>
                        @endif

                        @if(request('semester'))
                            <a href="?prodi={{ request('prodi') }}"
                            class="btn btn-sm btn-success">
                                Semester {{ request('semester') }} <span class="ml-1">&times;</span>
                            </a>
                        @endif

                        @if(request('kelompok'))
                            {{-- Tampilkan kode kelompok jika memungkinkan, atau ID sementara --}}
                            @php
                                $selectedK = $kelompokList->where('id_kelompok', request('kelompok'))->first();
                                $labelK = $selectedK ? $selectedK->kode_kelompok : request('kelompok');
                            @endphp
                            <a href="?prodi={{ request('prodi') }}&semester={{ request('semester') }}"
                            class="btn btn-sm btn-warning">
                                Kelompok {{ $labelK }} <span class="ml-1">&times;</span>
                            </a>
                        @endif
                        <a href="{{ url()->current() }}"
                        class="btn btn-sm btn-outline-danger">
                            Clear
                        </a>
                    </div>
                </div>
            @endif
            
            @if($isComplete)
            <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:1rem;">

                {{-- KALENDER --}}
                <div class="d-flex align-items-center" style="gap:1rem;">

                    {{-- DROPDOWN PERTEMUAN --}}
                    <div class="d-flex align-items-center" style="gap:.5rem;">
                        <label class="mb-0 text-muted">
                            <i class="fas fa-list mr-1"></i> Pertemuan
                        </label>

                        <select id="pertemuan-select"
                                class="form-control form-control-sm"
                                style="width: 120px;">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">
                                    Pertemuan {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- INPUT TANGGAL (OPSIONAL) --}}
                    <div class="d-flex align-items-center" style="gap:.5rem;">
                        <label class="mb-0 text-muted">
                            <i class="fas fa-calendar-alt mr-1"></i> Tanggal
                        </label>

                        <input type="date"
                            id="tanggal-absensi"
                            class="form-control form-control-sm"
                            style="width: 160px">
                    </div>
                </div>

                {{-- ABSENSI ALL --}}
                <div class="btn-group btn-group-sm absensi-all-wrapper">
                    <button class="btn btn-outline-success btn-absen-all" data-status="hadir">Hadir All</button>
                    <button class="btn btn-outline-warning btn-absen-all" data-status="izin">Izin All</button>
                    <button class="btn btn-outline-danger btn-absen-all" data-status="alpha">Alpha All</button>
                </div>
            </div>

            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="thead-light">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2" class="text-center">Nama</th>
                        <th rowspan="2">Status Hari Ini</th>
                        <th rowspan="2">Tanggal</th>

                        {{-- Header besar --}}
                        <th colspan="12">Pertemuan</th>

                        <th rowspan="2">Total Hadir</th>
                    </tr>

                    <tr>
                        {{-- Sub kolom pertemuan --}}
                        @for ($p = 1; $p <= 12; $p++)
                            <th class="pertemuan-col">
                                {{ $p }}
                            </th>
                        @endfor
                    </tr>
                </thead>

                <tbody>
                @forelse($mahasiswi as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        
                        {{-- PERUBAHAN 3: Ganti $m->nama jadi $m->nama_mahasiswi --}}
                        <td class="text-left">{{ $m->nama_mahasiswi }}</td>

                        {{-- STATUS HARI INI --}}
                        <td>
                            {{-- PERUBAHAN 4: Ganti $m->id jadi $m->id_mahasiswi --}}
                            <div class="btn-group btn-group-sm attendance-btns" 
                                data-id="{{ $m->id_mahasiswi }}"> 

                                <button type="button"
                                    class="btn btn-outline-success"
                                    data-status="hadir">
                                    Hadir
                                </button>

                                <button type="button"
                                    class="btn btn-outline-warning"
                                    data-status="izin">
                                    Izin
                                </button>

                                <button type="button"
                                    class="btn btn-outline-danger"
                                    data-status="alpha">
                                    Alpha
                                </button>
                            </div>

                            {{-- hidden input sementara --}}
                            <input type="hidden" class="attendance-value" value="">
                        </td>

                        {{-- KOLOM TANGGAL (DINAMIS) --}}
                        <td class="tanggal-col text-muted">
                            —
                        </td>

                        {{-- RIWAYAT PERTEMUAN (VERSI BARU: BACA DARI DATABASE) --}}
                        @for ($p = 1; $p <= 12; $p++)
                            <td class="pertemuan-col">
                                @php
                                    // Cek apakah ada riwayat di pertemuan ke-$p
                                    // Ini mengambil data dari Controller ($m->riwayat)
                                    $statusPertemuan = $m->riwayat[$p] ?? null;
                                @endphp

                                @if($statusPertemuan)
                                    {{-- Jika ada isinya, tampilkan Badge --}}
                                    @php
                                        $badgeClass = 'badge-info';
                                        if ($statusPertemuan == 'hadir') $badgeClass = 'badge-success';
                                        if ($statusPertemuan == 'izin')  $badgeClass = 'badge-warning';
                                        if ($statusPertemuan == 'alpha') $badgeClass = 'badge-danger';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $statusPertemuan }}
                                    </span>
                                @else
                                    {{-- Jika kosong, tampilkan strip --}}
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        @endfor

                        {{-- TOTAL HADIR --}}
                        <td class="total-hadir-col">
                            {{-- Ambil data dari controller, default 0 jika null --}}
                            @php $total = $m->total_hadir ?? 0; @endphp 

                            <span class="badge {{ $total > 0 ? 'badge-success' : 'badge-secondary' }} total-hadir">
                                {{ $total }}
                            </span>
                        </td>

                        {{-- DATA TETAP ADA --}}
                        <input type="hidden" value="{{ $m->prodi }}">
                        <input type="hidden" value="{{ $m->semester }}">
                        
                        {{-- PERUBAHAN 5: Ganti $m->kelompok jadi $m->id_kelompok --}}
                        <input type="hidden" value="{{ $m->id_kelompok }}">
                    </tr>
                @empty
                    <tr>
                        <td colspan="17" class="text-muted">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            @endif
            {{-- PUSH KE PERTEMUAN --}}
            @if($isComplete)
                <div class="d-flex justify-content-end mt-3">
                    {{-- Tambahkan data-toggle dan data-target --}}
                    <button type="button" 
                            class="btn btn-sm btn-primary mr-2" 
                            data-toggle="modal" 
                            data-target="#modalExport">
                        <i class="fas fa-file-word mr-1"></i></i> Export
                    </button>
                    <button
                        type="button"
                        class="btn btn-sm btn-success btn-push">
                        <i class="fas fa-level-down-alt mr-1"></i>
                        Push ke Pertemuan
                    </button>
                </div>
            @endif
        </div>
    </div>
    @if(!$isComplete)
        <div class="alert alert-warning d-flex align-items-center">
            <i class="fas fa-info-circle mr-2"></i>
            <span>
                @if(!$hasProdi && !$hasSemester && !$hasKelompok)
                    Silakan pilih <strong>Program Studi</strong>, <strong>Semester</strong>, dan <strong>Kelompok</strong> terlebih dahulu.
                @elseif($hasProdi && !$hasSemester)
                    Silakan pilih <strong>Semester</strong>.
                @elseif($hasProdi && $hasSemester && !$hasKelompok)
                    Silakan pilih <strong>Kelompok</strong>.
                @elseif(!$hasProdi && $hasSemester)
                    Silakan pilih <strong>Program Studi</strong>.
                @else
                    Lengkapi filter terlebih dahulu.
                @endif
            </span>
        </div>
    @endif
</div>
{{-- MODAL EXPORT PDF --}}
<div class="modal fade" id="modalExport" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Export Absensi</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      {{-- 🔥 WAJIB ADA --}}
      <div class="modal-body">
        {{-- PILIH BULAN --}}
        <div class="form-group">
          <label class="font-weight-bold">
            <i class="fas fa-calendar mr-1"></i> Bulan
          </label>
          <select class="form-control form-control-sm" id="bulan-export">
            @php
              $bulanList = [
                1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
              ];
            @endphp
            @foreach($bulanList as $num=>$nama)
              <option value="{{ $num }}">{{ $nama }}</option>
            @endforeach
          </select>
        </div>
        {{-- INPUT TANGGAL HEADER --}}
        <div class="form-group">
        <label class="font-weight-bold">
        <i class="fas fa-calendar-alt mr-1"></i> Tanggal Header Tabel (Urut dari Kiri ke Kanan)
        </label>

        <div class="row">
            @for($i=1;$i<=4;$i++)
            <div class="col-6 mb-2">
                <input type="date"
                    class="form-control form-control-sm tanggal-header"
                    data-slot="{{ $i }}"
                    placeholder="Tanggal {{ $i }}"
                    title="Tanggal {{ $i }} → kolom ke-{{ $i }} di tabel export">
            </div>
            @endfor
        </div>

        <small class="text-muted">
            Maksimal 4 tanggal untuk header tabel Word
        </small>
        </div>
        {{-- PILIH PERTEMUAN --}}
        <div class="form-group">
          <label class="font-weight-bold">Pertemuan</label>

          <div class="custom-control custom-checkbox mb-2">
            <input type="checkbox" class="custom-control-input" id="checkAllPertemuan">
            <label class="custom-control-label" for="checkAllPertemuan">
              Pilih Semua
            </label>
          </div>

          <div class="row">
            @for($i=1;$i<=12;$i++)
              <div class="col-4 mb-2">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox"
                         class="custom-control-input check-pertemuan"
                         id="p{{ $i }}"
                         value="{{ $i }}">
                  <label class="custom-control-label" for="p{{ $i }}">
                    Pertemuan {{ $i }}
                  </label>
                </div>
              </div>
            @endfor
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button class="btn btn-success" id="btn-process-export">
          Download
        </button>
      </div>

    </div>
  </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
console.log('SCRIPT LOAD');

// Helper untuk set status visual tombol
function setActiveStatus(group, status) {
    group.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
    const activeBtn = group.querySelector(`[data-status="${status}"]`);
    if (activeBtn) activeBtn.classList.add('active');
}

// Helper untuk update Badge di kolom pertemuan
function updateMeetingBadge(cell, status) {
    let badgeClass = 'badge-info';
    if (status === 'hadir') badgeClass = 'badge-success';
    if (status === 'izin') badgeClass = 'badge-warning';
    if (status === 'alpha') badgeClass = 'badge-danger';
    cell.innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
}

function submitAbsensi(button, status) {
    const group = button.closest('.attendance-btns');
    const row = group.closest('tr');
    
    // JS tidak perlu diubah, dia ambil dari data-id HTML yang sudah kita perbaiki
    const mahasiswiId = group.dataset.id; 

    const inputTanggal = document.getElementById('tanggal-absensi');
    const tanggal = inputTanggal ? inputTanggal.value : null;

    fetch("{{ route('absensi.simpan') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            mahasiswi_id: mahasiswiId,
            tanggal: tanggal,
            status: status
        })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            if(typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menyimpan data absensi.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
            return;
        }

        // ✅ status hari ini
        setActiveStatus(group, status);

        // ✅ kolom tanggal (BUKAN pertemuan)
        const tanggalCell = row.querySelector('.tanggal-col');
        if (tanggalCell) {
            tanggalCell.textContent = tanggal ?? '—';
        }

        // ✅ total hadir
        const totalBadge = row.querySelector('.total-hadir');
        if (totalBadge) {
            totalBadge.textContent = data.total_hadir;
            totalBadge.className =
                'badge ' +
                (data.total_hadir > 0 ? 'badge-success' : 'badge-secondary') +
                ' total-hadir';
        }
    })
    .catch(err => {
        console.error(err);
        // Error jaringan
        if(typeof Swal !== 'undefined') {
             Swal.fire({ icon: 'error', title: 'Error Jaringan', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    @if($isComplete)
        refreshData();
    @endif
    // Listener Tombol Status (Hadir/Izin/Alpha)
    document.querySelectorAll('.attendance-btns button').forEach(btn => {
        btn.addEventListener('click', function () {
            if (this.hasAttribute('disabled')) return;
            submitAbsensi(this, this.dataset.status);
        });
    });

    // Listener Tombol Absen All
    document.querySelectorAll('.btn-absen-all').forEach(btn => {
        btn.addEventListener('click', function () {
            const status = this.dataset.status;
            document.querySelectorAll('.attendance-btns').forEach(group => {
                const button = group.querySelector(`[data-status="${status}"]`);
                if (button && !button.hasAttribute('disabled')) {
                    submitAbsensi(button, status);
                }
            });
        });
    });

    // Listener Tombol PUSH
    // Listener Tombol PUSH (Update dengan SweetAlert & Promise)
    document.querySelector('.btn-push')?.addEventListener('click', function () {
        
        // 1. Ambil Data Pertemuan & Tanggal
        const pertemuan = parseInt(document.getElementById('pertemuan-select').value);
        const tanggal = document.getElementById('tanggal-absensi')?.value || null;

        // Array untuk menampung semua proses fetch
        let promises = [];
        let count = 0; // Hitung berapa baris yang diproses

        // 2. Loop setiap baris data
        document.querySelectorAll('tbody tr').forEach(row => {
            const group = row.querySelector('.attendance-btns');
            if (!group) return;

            const activeBtn = group.querySelector('button.active');
            if (!activeBtn) return; // Skip jika belum ada status yg dipilih

            const status = activeBtn.dataset.status;
            const mahasiswiId = group.dataset.id;
            const cells = row.querySelectorAll('.pertemuan-col');

            count++; // Tambah counter

            // Masukkan fetch ke dalam variable promise
            const request = fetch("{{ route('absensi.push') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    mahasiswi_id: mahasiswiId,
                    pertemuan: pertemuan,
                    tanggal: tanggal,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;

                // Update UI Badge Pertemuan
                updateMeetingBadge(cells[pertemuan - 1], status);

                // Update Kolom Tanggal
                const allCells = row.querySelectorAll('td');
                const tanggalCell = allCells[3]; // Asumsi kolom ke-4 adalah tanggal
                if (tanggalCell && tanggal) {
                    tanggalCell.innerHTML = `<span class="badge badge-info">${tanggal}</span>`;
                }

                // Update Total Hadir
                const totalBadge = row.querySelector('.total-hadir');
                if (totalBadge) {
                    totalBadge.textContent = data.total_hadir;
                    totalBadge.className = 'badge ' + 
                        (data.total_hadir > 0 ? 'badge-success' : 'badge-secondary') + 
                        ' total-hadir';
                }
            });

            promises.push(request);
        });

        // 3. Cek apakah ada data yang diproses
        if (count === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak ada data',
                text: 'Silakan isi status kehadiran mahasiswa terlebih dahulu.',
            });
            return;
        }

        // 4. Tampilkan Loading (Opsional tapi bagus untuk UX)
        let timerInterval;
        Swal.fire({
            title: 'Sedang memproses...',
            html: 'Mohon tunggu sebentar.',
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // 5. Tunggu semua fetch selesai, baru tampilkan SUKSES
        Promise.all(promises).then(() => {
            // Tutup loading & Tampilkan Toast Sukses
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: `Pertemuan ${pertemuan} berhasil disimpan!`
            });
        }).catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Gagal menyimpan sebagian data.'
            });
        });
    });
});
// Di file blade, bagian refreshData()
function refreshData() {
    fetch("{{ route('absensi.refresh') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            // Tambahkan trim() di sini
            prodi: "{{ trim(request('prodi')) }}", 
            semester: "{{ trim(request('semester')) }}",
            kelompok: "{{ trim(request('kelompok')) }}"
        })
    })
    .then(res => res.json())
    .then(res => {
        if (!res.success) {
            // ❌ HAPUS: alert('Gagal refresh data');
            // ✅ GANTI JADI:
            Swal.fire({
                icon: 'error',
                title: 'Gagal Refresh',
                text: 'Tidak dapat memuat data terbaru.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            return;
        }

        const data = res.data;

        document.querySelectorAll('tbody tr').forEach(row => {
            const group = row.querySelector('.attendance-btns');
            if (!group) return;

            const id = group.dataset.id;
            const cells = row.querySelectorAll('.pertemuan-col');
            const totalBadge = row.querySelector('.total-hadir');

            cells.forEach(cell => {
                cell.innerHTML = '<span class="text-muted">—</span>';
            });

            if (!data[id]) return;

            Object.entries(data[id].riwayat).forEach(([p, status]) => {
                const index = parseInt(p) - 1;
                if (cells[index]) updateMeetingBadge(cells[index], status);
            });

            if (totalBadge) {
                totalBadge.textContent = data[id].total_hadir;
                totalBadge.className =
                    'badge ' +
                    (data[id].total_hadir > 0 ? 'badge-success' : 'badge-secondary') +
                    ' total-hadir';
            }
        });
    });
}
document.getElementById('checkAllPertemuan')?.addEventListener('change', function() {
    const isChecked = this.checked;
    document.querySelectorAll('.check-pertemuan').forEach(el => {
        el.checked = isChecked;
    });
});

// 2. Handle Klik Tombol Download
document.getElementById('btn-process-export')?.addEventListener('click', function() {
    
    // Ambil pertemuan yang dipilih
    const selected = [];
    document.querySelectorAll('.check-pertemuan:checked').forEach(el => {
        selected.push(el.value);
    });

    if (selected.length === 0) {
        // ❌ HAPUS: alert('Harap pilih minimal satu pertemuan!');
        // ✅ GANTI JADI:
        Swal.fire({
            icon: 'warning',
            title: 'Belum memilih pertemuan',
            text: 'Harap centang minimal satu pertemuan untuk diexport.',
            confirmButtonColor: '#f6c23e'
        });
        return;
    }

    // --- CARA BARU: BUAT FORM SEMENTARA & SUBMIT ---
    // Ini menghindari error Blob dan Mixed Content
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('absensi.export') }}";
    form.style.display = 'none';

    // Tambahkan CSRF Token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = "{{ csrf_token() }}";
    form.appendChild(csrfInput);

    // Tambahkan Data Prodi, Semester, Kelompok
    const prodiInput = document.createElement('input');
    prodiInput.type = 'hidden';
    prodiInput.name = 'prodi';
    prodiInput.value = "{{ request('prodi') }}";
    form.appendChild(prodiInput);

    const smtInput = document.createElement('input');
    smtInput.type = 'hidden';
    smtInput.name = 'semester';
    smtInput.value = "{{ request('semester') }}";
    form.appendChild(smtInput);

    const klpInput = document.createElement('input');
    klpInput.type = 'hidden';
    klpInput.name = 'kelompok';
    klpInput.value = "{{ request('kelompok') }}";
    form.appendChild(klpInput);

    // Tambahkan Array Pertemuan (name="pertemuan[]")
    selected.forEach(val => {
        const pInput = document.createElement('input');
        pInput.type = 'hidden';
        pInput.name = 'pertemuan[]'; // Perhatikan kurung siku []
        pInput.value = val;
        form.appendChild(pInput);
    });
    // 🔥 Tambahkan Bulan
    const bulan = document.getElementById('bulan-export').value;

    const bulanInput = document.createElement('input');
    bulanInput.type = 'hidden';
    bulanInput.name = 'bulan';
    bulanInput.value = bulan;
    form.appendChild(bulanInput);

    // 🔥 Ambil tanggal header (tgl1 - tgl4)
    document.querySelectorAll('.tanggal-header').forEach(input => {
        const slot = input.dataset.slot;
        if (input.value) {
            const tglInput = document.createElement('input');
            tglInput.type = 'hidden';
            tglInput.name = `tgl${slot}`;
            tglInput.value = input.value;
            form.appendChild(tglInput);
        }
    });

    document.body.appendChild(form);

    // 1️⃣ Tutup modal DULU
    $('#modalExport').modal('hide');

    // 2️⃣ Lepaskan fokus dari tombol (INI PENTING)
    this.blur();

    // 3️⃣ Baru submit (beri delay kecil biar modal benar-benar selesai)
    setTimeout(() => {
        form.submit();
        document.body.removeChild(form);
    }, 300);
});
</script>
@endpush
<style>
/* kalender transparan */
.tanggal-picker {
    background-color: transparent;
    border: 1px solid #ced4da;
}
/* kalender aktif */
.tanggal-picker.has-value {
    background-color: #fff;
}
/* TOTAL HADIR CENTER */
th.total-hadir-col,
td.total-hadir-col {
    vertical-align: middle !important;
    text-align: center;
    white-space: nowrap;
}
/* PADATKAN HEADER TABLE */
.table thead th {
    padding: 0.4rem 0.5rem;
    vertical-align: middle !important;
    text-align: center;
    font-size: 0.9rem;
}
/* SUB HEADER PERTEMUAN (angka 1 2 3 dst) */
.table thead tr:nth-child(2) th {
    padding: 0.25rem;
    font-size: 0.85rem;
}
/* CENTER KOLOM UTAMA */
.table th,
.table td {
    vertical-align: middle !important;
}

/* NO */
.table td:first-child,
.table th:first-child {
    text-align: center;
    width: 50px;
}

/* NAMA */
.table td:nth-child(2),
.table th:nth-child(2) {
    text-align: center;
    white-space: nowrap;
}
/* STATUS HARI INI */
.table td:nth-child(3),
.table th:nth-child(3) {
    text-align: center;
}
.pertemuan-col {
    width: 45px;
    padding: 0.25rem !important;
    font-size: 0.85rem;
}
.attendance-btns .btn.active {
    color: #fff !important;
}

.attendance-btns .btn-outline-success.active {
    background-color: #28a745;
}

.attendance-btns .btn-outline-warning.active {
    background-color: #ffc107;
    color: #212529 !important;
}

.attendance-btns .btn-outline-danger.active {
    background-color: #dc3545;
}

</style>
@endsection
