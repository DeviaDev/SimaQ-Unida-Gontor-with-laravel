@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- JUDUL HALAMAN --}}
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-quran mr-2"></i> {{ $title }}
    </h1>

    {{-- BAGIAN 1: FILTER (Prodi, Semester, Kelompok) --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            @php
                $hasProdi    = request()->filled('prodi');
                $hasSemester = request()->filled('semester');
                $hasKelompok = request()->filled('kelompok');
                $isComplete  = $hasProdi && $hasSemester && $hasKelompok;
            @endphp

            {{-- A. Filter Prodi --}}
            <h6 class="text-muted mb-2"><i class="fas fa-filter mr-1"></i> Filter Program Studi</h6>
            <div class="d-flex flex-wrap mb-4" style="gap: .5rem;">
                @php
                    $prodi = ['PAI','PBA','TBI','PBA INTERNASIONAL','IQT','AFI','PM','HES','MB','EI','HI', 'ILKOM', 'TI', 'AGRO','GIZI','FARM'];
                @endphp
                @foreach ($prodi as $p)
                    <a href="?prodi={{ trim($p) }}&semester={{ trim(request('semester')) }}"
                       class="btn btn-sm btn-outline-primary {{ request('prodi') == $p ? 'active' : '' }}">
                        {{ $p }}
                    </a>
                @endforeach
            </div>

            {{-- B. Filter Semester --}}
            <h6 class="text-muted mb-2"><i class="fas fa-layer-group mr-1"></i> Filter Semester</h6>
            <div class="d-flex flex-wrap" style="gap: .5rem;">
                @for ($s = 1; $s <= 8; $s++)
                    <a href="?prodi={{ trim(request('prodi')) }}&semester={{ $s }}"
                       class="btn btn-sm btn-outline-success {{ request('semester') == $s ? 'active' : '' }}">
                        Semester {{ $s }}
                    </a>
                @endfor
            </div>

            {{-- C. Filter Kelompok --}}
            @if(!empty($kelompokList) && count($kelompokList) > 0)
                <h6 class="text-muted mt-4 mb-2"><i class="fas fa-users mr-1"></i> Filter Kelompok</h6>
                <div class="d-flex flex-wrap" style="gap: .5rem;">
                    @foreach ($kelompokList as $k)
                        <a href="?prodi={{ trim(request('prodi')) }}&semester={{ trim(request('semester')) }}&kelompok={{ trim($k) }}"
                           class="btn btn-sm btn-outline-warning {{ request('kelompok') == $k ? 'active' : '' }}">
                            Kelompok {{ $k }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- BAGIAN 2: TABLE DATA --}}
    @if($isComplete)
    <div class="card shadow">
        <div class="card-body">
            @if($isComplete)
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">

                    {{-- KIRI: Judul + filter aktif --}}
                    <div class="d-flex align-items-center flex-wrap" style="gap:.5rem;">
                        <h5 class="mb-0 mr-2">Absensi Tilawah</h5>

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
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" class="align-middle">No</th>
                            <th rowspan="2" class="align-middle" style="min-width: 200px;">Nama</th>
                            <th rowspan="2" class="align-middle" style="width: 80px;">Khatam</th>
                            <th colspan="30" rowspan="1" >Juz Mengaji</th>
                            <th rowspan="2" class="align-middle">Total Juz</th>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= 30; $i++)
                                <th style="width: 35px; font-size: 12px;">{{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mahasiswi as $index => $m)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-left">
                                {{ $m->nama }}
                            </td>
                            
                            {{-- Input Khatam --}}
                            <td>
                                <input type="number" class="form-control form-control-sm input-khatam" 
                                       data-id="{{ $m->id }}" value="{{ $m->khatam_ke }}" min="1" style="width: 60px;">
                            </td>

                            {{-- CHECKBOX Juz 1-30 --}}
                            @for ($j = 1; $j <= 30; $j++)
                                <td class="text-center p-0 align-middle">
                                    <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <input type="checkbox" 
                                            name="juz_{{ $m->id }}" 
                                            class="update-juz"
                                            data-id="{{ $m->id }}" 
                                            value="{{ $j }}"
                                            style="cursor:pointer; transform: scale(1.2);"
                                            {{ in_array($j, $m->juz_sekarang_array) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            @endfor

                            {{-- Total Juz --}}
                            <td class="text-center font-weight-bold">
                                <span id="total-juz-{{ $m->id }}">{{ $m->total_juz }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="34" class="text-center text-muted">Data tidak ditemukan pada kelompok ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Tombol Simpan (DIPINDAHKAN KE KANAN) --}}
        {{-- Menggunakan justify-content-end --}}
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-end">
            <div>
                <button id="btn-simpan-all" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    @else
        {{-- ALERT DINAMIS (Tetap dimunculkan saat filter belum lengkap) --}}
        <div class="alert alert-warning d-flex align-items-center">
            <i class="fas fa-info-circle mr-2"></i>
            <span>
                @if(!$hasProdi && !$hasSemester && !$hasKelompok)
                    Silakan pilih <strong>Program Studi</strong>, <strong>Semester</strong>, dan <strong>Kelompok</strong> terlebih dahulu untuk input.
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

        {{-- ========================================== --}}
        {{-- MODIFIKASI: Wrapper IF untuk menyembunyikan Leaderboard jika ada filter --}}
        {{-- Leaderboard HANYA muncul jika TIDAK ADA prodi, semester, maupun kelompok yg dipilih --}}
        {{-- ========================================== --}}
        
        @if(!$hasProdi && !$hasSemester && !$hasKelompok)
        
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list-ol mr-1"></i> Data Seluruh Mahasiswi (Urut Total Juz Tertinggi)
                        </h6>
                        {{-- KANAN: Tombol Export & Info --}}
                        <div class="d-flex align-items-center">
                            {{-- Tombol Trigger Modal --}}
                            <small class="text-muted font-italic mr-3">
                                *Pilih filter di atas untuk input data (Edit).
                            </small>
                            <button type="button" class="btn btn-sm btn-success mr-3 shadow-sm" data-toggle="modal" data-target="#modalExport">
                                <i class="fas fa-file-word mr-1"></i> Export Docx
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($leaderboard) && count($leaderboard) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">No</th>
                                        <th class="text-center">Nama Mahasiswi</th>
                                        <th style="width: 15%">Prodi</th>
                                        <th style="width: 10%">Semester</th>
                                        <th style="width: 10%">Kelompok</th>
                                        <th style="width: 15%" class="bg-primary text-white">Total Juz</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboard as $idx => $row)
                                    <tr>
                                        {{-- Penomoran Pagination --}}
                                        <td>{{ $leaderboard->firstItem() + $idx }}</td>
                                        
                                        <td class="text-left text-dark">
                                            {{ $row->nama }}
                                        </td>
                                        <td>
                                            {{ $row->prodi }}
                                        </td>
                                        <td>{{ $row->semester }}</td>
                                        <td>{{ $row->kelompok }}</td>
                                        
                                        <td class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                            {{ $row->total_juz }} 
                                            <small class="text-muted" style="font-size: 0.7em;">Juz</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Tombol Navigasi Halaman (Pagination) --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{-- Pakai parameter 'pagination::bootstrap-4' atau 'pagination::bootstrap-5' --}}
                            {{ $leaderboard->links('pagination::bootstrap-4') }}
                        </div>

                    @else
                        {{-- Tampilan Kosong --}}
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-database fa-3x text-gray-300"></i>
                            </div>
                            <h5 class="text-gray-500">Belum ada data tilawah.</h5>
                            <p class="text-muted small">Silakan filter dan input data terlebih dahulu.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif 
</div>
{{-- ========================================== --}}
{{-- MODAL EXPORT --}}
{{-- ========================================== --}}
<div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="modalExportLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('tilawah.export') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExportLabel"><i class="fas fa-file-word text-success mr-2"></i>Export Leaderboard</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        Data akan diurutkan berdasarkan Total Juz tertinggi.
                    </div>

                    {{-- Input Limit --}}
                    <div class="form-group">
                        <label for="limit">Jumlah Mahasiswi (Top Rank)</label>
                        <input type="number" class="form-control" name="limit" id="limit" value="10" min="1" required>
                        <small class="text-muted">Masukkan jumlah data yang ingin ditampilkan (misal: 10 besar, 50 besar).</small>
                    </div>

                    {{-- Input Bulan --}}
                    <div class="form-group">
                        <label for="bulan">Keterangan Bulan</label>
                        <select class="form-control" name="bulan" id="bulan" required>
                            <option value="">-- Pilih Bulan --</option>
                            @php
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            @endphp
                            @foreach($months as $month)
                                <option value="{{ $month }}">{{ $month }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Bulan ini akan tertulis di judul laporan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Download Docx</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($isComplete)
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Simpan nilai lama saat input khatam diklik/focus
        $('.input-khatam').on('focus', function() {
            $(this).data('prev', $(this).val());
        });

        // --- 1. LOGIKA VALIDASI KHATAM (NAIK & TURUN) ---
        $('.input-khatam').on('change', function() {
            var row = $(this).closest('tr');
            var newVal = parseInt($(this).val()) || 1;
            var oldVal = parseInt($(this).data('prev')) || 1;
            
            // Hitung jumlah checkbox yg sedang dicentang
            var juzCount = row.find('.update-juz:checked').length;

            // Aturan 1: TIDAK BOLEH TURUN KHATAM
            if (newVal < oldVal) {
                if(typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Bisa Turun!',
                        text: 'Anda tidak dapat mengurangi jumlah khatam yang sudah tercapai.',
                        confirmButtonColor: '#d33',
                    });
                } else {
                    alert('Tidak bisa mengurangi jumlah Khatam!');
                }
                $(this).val(oldVal); // Kembalikan ke nilai lama
                return;
            }

            // Aturan 2: TIDAK BOLEH NAIK KECUALI SUDAH 30 JUZ
            if (newVal > oldVal) {
                if (juzCount < 30) {
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning', 
                            title: 'Belum Selesai!',
                            text: 'Harap selesaikan 30 Juz (centang semua) di Khatam ini sebelum lanjut ke berikutnya.',
                            confirmButtonColor: '#f6c23e', // Warna warning
                        });
                    } else {
                        alert('Harap selesaikan 30 Juz terlebih dahulu!');
                    }
                    $(this).val(oldVal); // Kembalikan ke nilai lama
                    return; 
                } else {
                    // SUKSES NAIK LEVEL
                    // Hapus semua centang checkbox untuk memulai khatam baru
                    row.find('.update-juz').prop('checked', false);
                }
            }

            // Jika lolos validasi, update nilai 'prev' dan update tampilan
            $(this).data('prev', newVal);
            updateVisual(row);
        });

        // --- 2. LOGIKA VISUAL & HITUNG TOTAL ---
        function updateVisual(row) {
            var id = row.find('.input-khatam').data('id');
            var khatam = parseInt(row.find('.input-khatam').val()) || 1;
            
            // Hitung jumlah checkbox yang SEDANG dicentang
            var juzCount = row.find('.update-juz:checked').length; 

            // Rumus Total: ((Khatam - 1) * 30) + Jumlah Centang Saat Ini
            var totalJuz = ((khatam - 1) * 30) + juzCount;
            $("#total-juz-" + id).text(totalJuz); 

            // Warnai checkbox yang dicentang
            row.find('.update-juz').each(function() {
                var td = $(this).closest('td');
                if ($(this).is(':checked')) {
                    td.css('background-color', '#c1e0faff'); // Hijau
                } else {
                    td.css('background-color', ''); // Putih
                }
            });
            
            row.addClass('was-edited'); 
        }

        // Listener saat Checkbox Juz diklik
        $('.update-juz').on('change', function() {
            var row = $(this).closest('tr');
            updateVisual(row);
        });

        // Jalankan saat pertama kali load
        $('tbody tr').each(function() {
            var inputKhatam = $(this).find('.input-khatam');
            // Set nilai awal untuk data-prev
            inputKhatam.data('prev', inputKhatam.val());
            updateVisual($(this));
        });

        // --- 3. LOGIKA SIMPAN KE SERVER (ARRAY VALUES) ---
        $('#btn-simpan-all').on('click', function() {
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            var dataToSave = [];
            
            $('tbody tr').each(function() {
                var row = $(this);
                var id = row.find('.input-khatam').data('id');
                var khatam = row.find('.input-khatam').val();
                
                // Ambil VALUE spesifik checkbox yang dicentang (misal: [3, 7, 10])
                var checkedValues = [];
                row.find('.update-juz:checked').each(function() {
                    checkedValues.push($(this).val());
                });

                if(id) {
                    dataToSave.push({
                        mahasiswi_id: id,
                        khatam_ke: khatam,
                        juz: checkedValues // Kirim Array ke Controller
                    });
                }
            });

            $.ajax({
                url: "{{ route('tilawah.simpanSemua') }}",
                type: "POST",
                data: { 
                    _token: "{{ csrf_token() }}", 
                    data: dataToSave 
                },
                success: function(response) {
                    if(response.success) {
                        if(typeof Swal !== 'undefined') {
                            Swal.fire('Berhasil!', 'Data berhasil disimpan.', 'success');
                        } else {
                            alert('Berhasil menyimpan data!');
                        }
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    if (xhr.status === 419) {
                        alert('Sesi habis. Refresh halaman.');
                        location.reload(); 
                    } else {
                        alert('Gagal menyimpan data!');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

    });
</script>
@endif

@endsection