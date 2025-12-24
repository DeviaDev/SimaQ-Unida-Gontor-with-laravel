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
                    <a href="?prodi={{ $p }}&semester={{ request('semester') }}"
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
                    <a href="?prodi={{ request('prodi') }}&semester={{ $s }}"
                       class="btn btn-sm btn-outline-success {{ request('semester') == $s ? 'active' : '' }}">
                        Semester {{ $s }}
                    </a>
                @endfor
            </div>

            {{-- Filter Kelompok --}}
            @if(!empty($kelompokList) && count($kelompokList) > 0)
                <h6 class="text-muted mt-4 mb-2">
                    <i class="fas fa-users mr-1"></i>
                    Filter Kelompok
                </h6>

                <div class="d-flex flex-wrap" style="gap: .5rem;">
                    @foreach ($kelompokList as $k)
                        <a href="?prodi={{ request('prodi') }}
                                &semester={{ request('semester') }}
                                &kelompok={{ $k }}"
                        class="btn btn-sm btn-outline-warning {{ request('kelompok') == $k ? 'active' : '' }}">
                            Kelompok {{ $k }}
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
                            <a href="?prodi={{ request('prodi') }}&semester={{ request('semester') }}"
                            class="btn btn-sm btn-warning">
                                Kelompok {{ request('kelompok') }} <span class="ml-1">&times;</span>
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
                        <th rowspan="2" class="text-left">Nama</th>
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
                        <td class="text-left">{{ $m->nama }}</td>

                        {{-- STATUS HARI INI --}}
                        <td>
                            <div class="btn-group btn-group-sm attendance-btns" 
                                data-id="{{ $m->id }}"> 

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
                        <input type="hidden" value="{{ $m->kelompok }}">
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-muted">
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
                    <!-- <button
                        type="button"
                        class="btn btn-sm btn-secondary btn-refresh">
                        <i class="fas fa-sync-alt mr-1"></i>
                        Refresh Data
                    </button> -->
                    <button
                        type="button"
                        class="btn btn-sm btn-primary btn-push">
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
@push('scripts')
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
            alert('Gagal menyimpan');
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
    .catch(err => console.error(err));
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
    document.querySelector('.btn-push')?.addEventListener('click', function () {

            const pertemuan = parseInt(
                document.getElementById('pertemuan-select').value
            );

            const tanggal = document.getElementById('tanggal-absensi')?.value || null;

            document.querySelectorAll('tbody tr').forEach(row => {
                const group = row.querySelector('.attendance-btns');
                if (!group) return;

                const activeBtn = group.querySelector('button.active');
                if (!activeBtn) return;

                const status = activeBtn.dataset.status;
                const mahasiswiId = group.dataset.id;
                const cells = row.querySelectorAll('.pertemuan-col');

                fetch("{{ route('absensi.push') }}", {
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

                    updateMeetingBadge(cells[pertemuan - 1], status);

                    // 🔥 update kolom tanggal
                    const allCells = row.querySelectorAll('td');
                    const tanggalCell = allCells[3];
                    if (tanggalCell && tanggal) {
                        tanggalCell.innerHTML = `<span class="badge badge-info">${tanggal}</span>`;
                    }

                    // 🔥 set status aktif
                    setActiveStatus(group, status);

                    // update total hadir
                    const totalBadge = row.querySelector('.total-hadir');
                    if (totalBadge) {
                        totalBadge.textContent = data.total_hadir;
                        totalBadge.className =
                            'badge ' +
                            (data.total_hadir > 0 ? 'badge-success' : 'badge-secondary') +
                            ' total-hadir';
                    }
                });
            });
        alert("Pertemuan " + pertemuan + " berhasil diisi");
    });
});
function refreshData() {
    fetch("{{ route('absensi.refresh') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            prodi: "{{ request('prodi') }}",
            semester: "{{ request('semester') }}",
            kelompok: "{{ request('kelompok') }}"
        })
    })
    .then(res => res.json())
    .then(res => {
        if (!res.success) {
            alert('Gagal refresh data');
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

        alert('Data berhasil direfresh dari database');
    });
}
// tombol refresh
// document.querySelector('.btn-refresh')
//     ?.addEventListener('click', refreshData);
</script>
@endpush
@endsection
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


