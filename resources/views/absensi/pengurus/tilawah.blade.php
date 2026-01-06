@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- JUDUL HALAMAN --}}
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-quran mr-2"></i> {{ $title }}
    </h1>

    {{-- BAGIAN 1: TABLE INPUT DATA --}}
    <div class="card shadow mb-5">
        {{-- Header Card Input (Tombol Export dipindah dari sini) --}}
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Input Data Tilawah</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="2" class="align-middle">No</th>
                            <th rowspan="2" class="align-middle" style="min-width: 250px;">Nama Pengurus</th>
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
                        @forelse($pengurus as $index => $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-left text-dark">
                                {{ $p->nama }}
                            </td>
                            
                            {{-- Input Khatam --}}
                            <td>
                                <input type="number" class="form-control form-control-sm input-khatam" 
                                       data-id="{{ $p->id }}" value="{{ $p->khatam_ke }}" min="1" style="width: 60px;">
                            </td>

                            {{-- CHECKBOX Juz 1-30 --}}
                            @for ($j = 1; $j <= 30; $j++)
                                <td class="text-center p-0 align-middle">
                                    <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <input type="checkbox" 
                                               name="juz_{{ $p->id }}" 
                                               class="update-juz"
                                               data-id="{{ $p->id }}" 
                                               value="{{ $j }}"
                                               style="cursor:pointer; transform: scale(1.2);"
                                               {{ in_array($j, $p->juz_sekarang_array) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            @endfor

                            {{-- Total Juz --}}
                            <td class="text-center font-weight-bold">
                                <span id="total-juz-{{ $p->id }}">{{ $p->total_juz }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="34" class="text-center text-muted">Belum ada data pengurus.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer py-3 d-flex flex-row align-items-center justify-content-end">
            <div>
                <button id="btn-simpan-all" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: LEADERBOARD (RANKING) --}}
    <div class="card shadow">
        {{-- Header Card Leaderboard (Tombol Export ditaruh disini, sejajar kanan) --}}
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-trophy mr-1"></i> Leaderboard Tilawah Pengurus
            </h6>
            
            <button type="button" class="btn btn-sm btn-success shadow-sm" data-toggle="modal" data-target="#modalExport">
                <i class="fas fa-file-word mr-1"></i> Export Laporan
            </button>
        </div>

        <div class="card-body">
            @if(isset($leaderboard) && count($leaderboard) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 5%">Rank</th>
                                <th class="text-center">Nama Pengurus</th>
                                <th style="width: 20%" class="bg-primary text-white">Total Juz</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard as $idx => $row)
                            <tr>
                                {{-- Nomor Urut Paginasi --}}
                                <td>{{ $idx + 1 + ($leaderboard->currentPage() - 1) * $leaderboard->perPage() }}</td>
                                
                                <td class="text-left text-dark">
                                    {{ $row->nama }}
                                </td>
                                
                                <td class="font-weight-bold text-primary" style="font-size: 1.1em;">
                                    {{ $row->total_juz }} 
                                    <small class="text-muted" style="font-size: 0.7em;">Juz</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $leaderboard->links('pagination::bootstrap-4') }}
                </div>

            @else
                <div class="text-center py-5">
                    <h5 class="text-gray-500">Belum ada data ranking.</h5>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- MODAL EXPORT --}}
<div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('pengurus.tilawah.export') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-word text-success mr-2"></i>Export Laporan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Keterangan Bulan</label>
                        <select class="form-control" name="bulan">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $month)
                                <option value="{{ $month }}">{{ $month }}</option>
                            @endforeach
                        </select>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        
        // Setup CSRF untuk AJAX
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // 1. Logic Visual & Hitung Total
        function updateVisual(row) {
            var id = row.find('.input-khatam').data('id');
            var khatam = parseInt(row.find('.input-khatam').val()) || 1;
            var juzCount = row.find('.update-juz:checked').length; 
            var totalJuz = ((khatam - 1) * 30) + juzCount;
            
            $("#total-juz-" + id).text(totalJuz); 

            row.find('.update-juz').each(function() {
                var td = $(this).closest('td');
                if ($(this).is(':checked')) {
                    td.css('background-color', '#c1e0faff'); 
                } else {
                    td.css('background-color', ''); 
                }
            });
        }

        // 2. Event Listeners
        $('.input-khatam').on('focus', function() { $(this).data('prev', $(this).val()); });
        
        $('.input-khatam').on('change', function() {
            var row = $(this).closest('tr');
            var newVal = parseInt($(this).val()) || 1;
            var oldVal = parseInt($(this).data('prev')) || 1;
            var juzCount = row.find('.update-juz:checked').length;

            // Aturan 1: TIDAK BOLEH TURUN KHATAM
            if (newVal < oldVal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Bisa Turun!',
                    text: 'Anda tidak dapat mengurangi jumlah khatam yang sudah tercapai.',
                    confirmButtonColor: '#d33',
                });
                $(this).val(oldVal); 
                return;
            }

            // Aturan 2: TIDAK BOLEH NAIK KECUALI SUDAH 30 JUZ
            if (newVal > oldVal) {
                if (juzCount < 30) {
                    Swal.fire({
                        icon: 'warning', 
                        title: 'Belum Selesai!',
                        text: 'Harap selesaikan 30 Juz (centang semua) di Khatam ini sebelum lanjut ke berikutnya.',
                        confirmButtonColor: '#f6c23e',
                    });
                    $(this).val(oldVal); 
                    return; 
                } else {
                    row.find('.update-juz').prop('checked', false);
                }
            }
            
            $(this).data('prev', newVal);
            updateVisual(row);
        });

        $('.update-juz').on('change', function() {
            updateVisual($(this).closest('tr'));
        });

        // Init saat load
        $('tbody tr').each(function() {
            var input = $(this).find('.input-khatam');
            input.data('prev', input.val());
            updateVisual($(this));
        });

        // 3. Simpan Semua
        $('#btn-simpan-all').on('click', function() {
            var btn = $(this);
            var originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            var dataToSave = [];
            $('tbody tr').each(function() {
                var row = $(this);
                var id = row.find('.input-khatam').data('id');
                var khatam = row.find('.input-khatam').val();
                var checkedValues = [];
                row.find('.update-juz:checked').each(function() {
                    checkedValues.push($(this).val());
                });

                if(id) {
                    dataToSave.push({
                        pengurus_id: id,
                        khatam_ke: khatam,
                        juz: checkedValues 
                    });
                }
            });

            $.ajax({
                url: "{{ route('pengurus.tilawah.simpan') }}",
                type: "POST",
                // Fix Error 419
                data: { 
                    _token: "{{ csrf_token() }}", 
                    data: dataToSave 
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Data tilawah pengurus disimpan. Halaman akan dimuat ulang.',
                            icon: 'success'
                        }).then((result) => {
                            location.reload(); 
                        });
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    if (xhr.status === 419) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sesi Habis',
                            text: 'Silakan refresh halaman dan login ulang.',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
@endsection