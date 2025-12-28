@extends('layouts/app')

@section('content')
<div class="container-fluid">

    {{-- Judul Halaman --}}
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-users mr-2"></i> {{ $title }}
    </h1>

    {{-- Filter Gedung (Selection) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @php
                $hasGedung = request()->filled('gedung');
            @endphp

            <h6 class="text-muted mb-2 filter-title">
                <i class="fas fa-building mr-1"></i> Filter Gedung
            </h6>

            <div class="d-flex flex-wrap" style="gap:.5rem;">
                @php
                    $listGedung = [
                        'Istanbul LT2', 'Istanbul LT3', 'Aula Unida', 'Musholla', 'Klaster'
                    ];
                @endphp

                @foreach($listGedung as $g)
                    <a href="?gedung={{ urlencode($g) }}"
                       class="btn btn-sm btn-outline-purple {{ request('gedung') == $g ? 'active' : '' }}">
                        {{ $g }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="card shadow">
        <div class="card-body">

            @if($hasGedung)
                {{-- BARIS 1: Judul + Active Filter + Clear --}}
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <div class="d-flex align-items-center flex-wrap" style="gap:.5rem;">
                        <h5 class="mb-0 mr-2">Data Muhafidzoh</h5>

                        {{-- Badge Filter Aktif --}}
                        <span class="btn btn-sm btn-purple">
                            {{ request('gedung') }} 
                            <a href="{{ url()->current() }}" class="text-white ml-1 text-decoration-none">&times;</a>
                        </span>

                        {{-- Tombol Clear --}}
                        <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-danger" title="Reset Filter">
                            Clear
                        </a>
                    </div>
                </div>

                {{-- BARIS 2: Toolbar Input & Action --}}
                <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:1rem;">

                    {{-- KIRI: Input Pertemuan & Tanggal --}}
                    <div class="d-flex align-items-center" style="gap:1rem;">
                        
                        {{-- Dropdown Pertemuan --}}
                        <div class="d-flex align-items-center" style="gap:.5rem;">
                            <label class="mb-0 text-muted">
                                <i class="fas fa-list mr-1"></i> Pertemuan
                            </label>
                            <select id="pertemuan-select" class="form-control form-control-sm" style="width: 120px;">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">Pertemuan {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Input Tanggal --}}
                        <div class="d-flex align-items-center" style="gap:.5rem;">
                            <label class="mb-0 text-muted">
                                <i class="fas fa-calendar-alt mr-1"></i> Tanggal
                            </label>
                            <input type="date" id="tanggal-absensi" class="form-control form-control-sm" style="width: 160px;">
                        </div>
                    </div>

                    {{-- KANAN: Tombol Absen Massal --}}
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-success btn-absen-all" data-status="hadir">Hadir All</button>
                        <button class="btn btn-outline-warning btn-absen-all" data-status="izin">Izin All</button>
                        <button class="btn btn-outline-danger btn-absen-all" data-status="alpha">Alpha All</button>
                    </div>

                </div>

                {{-- Tabel Data --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2" style="width: 50px;">No</th>
                                <th rowspan="2" class="text-center">Nama</th>
                                <th rowspan="2">Ket</th> 
                                <th rowspan="2">Klp</th>
                                <th rowspan="2" style="width: 180px;">Status Hari Ini</th>
                                <th rowspan="2" style="width: 100px;">Tanggal</th>
                                <th colspan="12">Riwayat Pertemuan</th>
                                <th rowspan="2">Total</th>
                            </tr>
                            <tr>
                                {{-- Sub Header Angka 1-12 --}}
                                @for($p=1; $p<=12; $p++)
                                    <th class="pertemuan-col">{{ $p }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($muhafidzoh as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-left">{{ $m->nama }}</td>
                                <td>{{ $m->ket ?? '-' }}</td>
                                <td>{{ $m->kelompok ?? '-' }}</td>

                                {{-- STATUS HARI INI (BUTTONS) --}}
                                <td>
                                    <div class="btn-group btn-group-sm attendance-btns" data-id="{{ $m->id }}">
                                        <button type="button" class="btn btn-outline-success" data-status="hadir">H</button>
                                        <button type="button" class="btn btn-outline-warning" data-status="izin">I</button>
                                        <button type="button" class="btn btn-outline-danger" data-status="alpha">A</button>
                                    </div>
                                </td>

                                {{-- KOLOM TANGGAL (JS Update) --}}
                                <td class="tanggal-col text-muted small">—</td>

                                {{-- RIWAYAT PERTEMUAN (LOOP 1-12) --}}
                                @for($p=1; $p<=12; $p++)
                                    <td class="pertemuan-col p-1">
                                        {{-- Placeholder Strip (Akan diupdate JS) --}}
                                        <span class="text-muted small">-</span>
                                    </td>
                                @endfor

                                {{-- TOTAL HADIR --}}
                                <td class="total-hadir-col">
                                    <span class="badge badge-success total-hadir">0</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="18" class="text-center text-muted py-4">
                                    Data Muhafidzoh tidak ditemukan untuk gedung ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer Tombol Push & Export --}}
                <div class="d-flex justify-content-end mt-3">
                    {{-- TOMBOL EXPORT (Trigger Modal) --}}
                    <button type="button" 
                            class="btn btn-sm btn-success mr-2" 
                            data-toggle="modal" 
                            data-target="#modalExport">
                        <i class="fas fa-file-word mr-1"></i> Export
                    </button>

                    <button type="button" class="btn btn-sm btn-primary btn-push">
                        <i class="fas fa-level-down-alt mr-1"></i> Push ke Pertemuan
                    </button>
                </div>

            @else
                {{-- Alert jika belum pilih gedung --}}
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>
                        Silakan pilih <strong>Gedung</strong> terlebih dahulu.
                    </span>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- MODAL EXPORT PDF/DOCX --}}
<div class="modal fade" id="modalExport" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Export Absensi Muhafidzoh</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

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
                <i class="fas fa-calendar-alt mr-1"></i> Tanggal Header Tabel (4 Kolom)
            </label>
            <div class="row">
                @for($i=1;$i<=4;$i++)
                <div class="col-6 mb-2">
                    <input type="date"
                        class="form-control form-control-sm tanggal-header"
                        data-slot="{{ $i }}"
                        placeholder="Tanggal {{ $i }}">
                </div>
                @endfor
            </div>
        </div>

        {{-- PILIH PERTEMUAN --}}
        <div class="form-group">
          <label class="font-weight-bold">Pertemuan yang Diexport</label>

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
                    P{{ $i }}
                  </label>
                </div>
              </div>
            @endfor
          </div>
        </div>

        {{-- CHECKBOX TANPA STAF --}}
        <hr>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="checkTanpaStaf" checked>
                <label class="custom-control-label font-weight-bold text-danger" for="checkTanpaStaf">
                    Export Tanpa Staf (Hanya Guru)
                </label>
                <small class="form-text text-muted">
                    Jika dicentang, data dengan keterangan <b>STAF</b> tidak akan dimasukkan ke file export.
                </small>
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
<script>
    // --- KONFIGURASI AJAX ---
    const URL_SIMPAN  = "{{ route('absensi_muhafidzoh.simpan') }}";
    const URL_PUSH    = "{{ route('absensi_muhafidzoh.push') }}";
    const URL_REFRESH = "{{ route('absensi_muhafidzoh.refresh') }}";
    const URL_EXPORT  = "{{ route('absensi_muhafidzoh.export') }}"; // Route baru
    const CSRF_TOKEN  = "{{ csrf_token() }}";

    // --- HELPER VISUAL ---
    function setActiveStatus(group, status) {
        group.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
        const activeBtn = group.querySelector(`[data-status="${status}"]`);
        if (activeBtn) activeBtn.classList.add('active');
    }

    function updateMeetingBadge(cell, status) {
        let badgeClass = 'badge-info';
        if (status === 'hadir') badgeClass = 'badge-success';
        if (status === 'izin') badgeClass = 'badge-warning';
        if (status === 'alpha') badgeClass = 'badge-danger';
        cell.innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
    }

    // --- LOGIKA REFRESH DATA (AUTO RUN) ---
    function refreshData() {
        fetch(URL_REFRESH, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN },
            body: JSON.stringify({ gedung: "{{ request('gedung') }}" })
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) return;
            const data = res.data;
            document.querySelectorAll('tbody tr').forEach(row => {
                const group = row.querySelector('.attendance-btns');
                if (!group) return;
                const id = group.dataset.id;
                const cells = row.querySelectorAll('.pertemuan-col');
                const totalBadge = row.querySelector('.total-hadir');

                cells.forEach(cell => { cell.innerHTML = '<span class="text-muted small">-</span>'; });

                if (data[id]) {
                    Object.entries(data[id].riwayat).forEach(([p, status]) => {
                        const index = parseInt(p) - 1; 
                        if (cells[index]) updateMeetingBadge(cells[index], status);
                    });
                    if (totalBadge) {
                        totalBadge.textContent = data[id].total_hadir;
                        totalBadge.className = 'badge ' + (data[id].total_hadir > 0 ? 'badge-success' : 'badge-secondary') + ' total-hadir';
                    }
                }
            });
        })
        .catch(err => console.error('Error fetching data:', err));
    }

    // --- LOGIKA SIMPAN ---
    function submitAbsensi(button, status) {
        const group = button.closest('.attendance-btns');
        const row   = group.closest('tr');
        const id    = group.dataset.id; 
        const tgl   = document.getElementById('tanggal-absensi').value;

        if(!tgl) { alert('Harap isi Tanggal terlebih dahulu!'); return; }

        fetch(URL_SIMPAN, {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN },
            body: JSON.stringify({ muhafidzoh_id: id, tanggal: tgl, status: status })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) { alert('Gagal menyimpan: ' + data.message); return; }
            setActiveStatus(group, status);
            row.querySelector('.tanggal-col').textContent = tgl;
            const totalEl = row.querySelector('.total-hadir');
            if(totalEl) totalEl.textContent = data.total_hadir;
        })
        .catch(err => console.error(err));
    }

    document.addEventListener('DOMContentLoaded', function () {
        
        // AUTO REFRESH
        @if($hasGedung)
            refreshData();
        @endif

        // Listener Status Individu
        document.querySelectorAll('.attendance-btns button').forEach(btn => {
            btn.addEventListener('click', function () { submitAbsensi(this, this.dataset.status); });
        });

        // Listener Absen All
        document.querySelectorAll('.btn-absen-all').forEach(btn => {
            btn.addEventListener('click', function () {
                const status = this.dataset.status;
                const tgl = document.getElementById('tanggal-absensi').value;
                if(!tgl) { alert('Isi tanggal dulu!'); return; }
                document.querySelectorAll('.attendance-btns').forEach(group => {
                    const button = group.querySelector(`[data-status="${status}"]`);
                    if (button) submitAbsensi(button, status);
                });
            });
        });

        // Listener Push Pertemuan
        document.querySelector('.btn-push')?.addEventListener('click', function () {
            const p = document.getElementById('pertemuan-select').value;
            const tgl = document.getElementById('tanggal-absensi').value;
            if(!tgl) { alert('Tanggal wajib diisi untuk Push!'); return; }
            if(!confirm(`Yakin push data tanggal ${tgl} ke Pertemuan ${p}?`)) return;

            document.querySelectorAll('tbody tr').forEach(row => {
                const group = row.querySelector('.attendance-btns');
                if(!group) return;
                const activeBtn = group.querySelector('button.active');
                if(!activeBtn) return; 
                const id = group.dataset.id;
                const status = activeBtn.dataset.status;
                const cells = row.querySelectorAll('.pertemuan-col');

                fetch(URL_PUSH, {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN },
                    body: JSON.stringify({ muhafidzoh_id: id, tanggal: tgl, status: status, pertemuan: p })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        if(cells[p-1]) updateMeetingBadge(cells[p-1], status);
                        const totalEl = row.querySelector('.total-hadir');
                        if(totalEl) totalEl.textContent = data.total_hadir;
                    }
                });
            });
            alert('Proses Push berjalan di background.');
        });

        // --- LISTENER EXPORT ---
        // 1. Check All
        document.getElementById('checkAllPertemuan')?.addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.check-pertemuan').forEach(el => { el.checked = isChecked; });
        });

        // 2. Klik Download
        document.getElementById('btn-process-export')?.addEventListener('click', function() {
            // Ambil pertemuan yang dipilih
            const selected = [];
            document.querySelectorAll('.check-pertemuan:checked').forEach(el => {
                selected.push(el.value);
            });

            if (selected.length === 0) {
                alert('Harap pilih minimal satu pertemuan!');
                return;
            }

            // Buat form hidden
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = URL_EXPORT;
            form.style.display = 'none';

            // CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = CSRF_TOKEN;
            form.appendChild(csrfInput);

            // Data Gedung (Filter saat ini)
            const gedungInput = document.createElement('input');
            gedungInput.type = 'hidden';
            gedungInput.name = 'gedung';
            gedungInput.value = "{{ request('gedung') }}";
            form.appendChild(gedungInput);

            // Data Tanpa Staf
            const tanpaStaf = document.getElementById('checkTanpaStaf').checked ? '1' : '0';
            const stafInput = document.createElement('input');
            stafInput.type = 'hidden';
            stafInput.name = 'tanpa_staf';
            stafInput.value = tanpaStaf;
            form.appendChild(stafInput);

            // Data Pertemuan Array
            selected.forEach(val => {
                const pInput = document.createElement('input');
                pInput.type = 'hidden';
                pInput.name = 'pertemuan[]';
                pInput.value = val;
                form.appendChild(pInput);
            });

            // Data Bulan
            const bulanInput = document.createElement('input');
            bulanInput.type = 'hidden';
            bulanInput.name = 'bulan';
            bulanInput.value = document.getElementById('bulan-export').value;
            form.appendChild(bulanInput);

            // Data Tanggal Header (tgl1 - tgl4)
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
            $('#modalExport').modal('hide');
            this.blur();

            setTimeout(() => {
                form.submit();
                document.body.removeChild(form);
            }, 300);
        });
    });
</script>
@endpush

<style>
    .pertemuan-col { width: 40px; font-size: 0.8rem; vertical-align: middle !important; padding: 4px !important; }
    .table th { vertical-align: middle !important; font-size: 0.9rem; text-align: center; }
    .table td { vertical-align: middle !important; }
    .attendance-btns .btn.active { color: white !important; }
    .attendance-btns .btn-outline-success.active { background-color: #28a745; }
    .attendance-btns .btn-outline-warning.active { background-color: #ffc107; color: black !important; }
    .attendance-btns .btn-outline-danger.active { background-color: #dc3545; }
    #tanggal-absensi { cursor: pointer; }

/* Custom Button Ungu */
.btn-outline-purple {
    color: #6f42c1;
    border-color: #6f42c1;
}
.btn-outline-purple:hover,
.btn-outline-purple.active {
    color: #fff;
    background-color: #6f42c1;
    border-color: #6f42c1;
}

/* Untuk Badge Filter Aktif */
.btn-purple {
    color: #fff;
    background-color: #6f42c1;
    border-color: #6f42c1;
}
.btn-purple:hover {
    color: #fff;
    background-color: #5a32a3; /* Sedikit lebih gelap saat hover */
}
</style>
@endsection