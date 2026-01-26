@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt mr-2"></i> {{ $title }}
        </h1>
    </div>

    {{-- Stats Row --}}
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Periode Tahfidz
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Pekan Ke-{{ $pekan }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Mahasiswi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_mahasiswi }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Muhafidzoh
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_muhafidzoh }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Kelompok LT
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $total_kelompok }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Stats Row --}}

    {{-- Content Row (Graph & Reminder & Pie Chart) --}}
    <div class="row">

        @push('styles')
            <style>
                .timeline-badge { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-top: 6px; background-color: #dc3545; }
                .reminder-body::-webkit-scrollbar { width: 6px; }
                .reminder-body::-webkit-scrollbar-thumb { background-color: #e3e6f0; border-radius: 10px; }
                /* Style untuk Leaderboard */
                .badge-rank-1 { background-color: #FFD700; color: #fff; border-radius: 50%; width: 25px; height: 25px; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; }
                .badge-rank-2 { background-color: #C0C0C0; color: #fff; border-radius: 50%; width: 25px; height: 25px; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; }
                .badge-rank-3 { background-color: #CD7F32; color: #fff; border-radius: 50%; width: 25px; height: 25px; display: inline-flex; justify-content: center; align-items: center; font-weight: bold; }
                .badge-rank-other { background-color: #f8f9fc; color: #858796; border-radius: 50%; width: 25px; height: 25px; display: inline-flex; justify-content: center; align-items: center; font-size: 0.8em; font-weight: bold; border: 1px solid #e3e6f0; }
            </style>
        @endpush

        {{-- KOLOM KIRI: Job Reminder & Sebaran Prodi --}}
        <div class="col-xl-4 col-lg-5">
            
            {{-- 1. Job Reminder (DYNAMIC) --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell mr-1"></i> Job Reminder & Alerts
                    </h6>
                    <span class="badge badge-danger badge-counter">{{ count($reminders) > 0 && $reminders[0]['type'] != 'success' ? count($reminders) : '' }}</span>
                </div>
                <div class="card-body reminder-body" style="max-height: 380px; overflow-y: auto;">
                    <ul class="timeline list-unstyled">
                        @foreach($reminders as $reminder)
                        <li class="mb-4 d-flex align-items-start">
                            {{-- Icon Badge --}}
                            <div class="mr-3 mt-1">
                                <div class="btn-circle btn-sm btn-{{ $reminder['type'] }}">
                                    <i class="{{ $reminder['icon'] }}"></i>
                                </div>
                            </div>
                            
                            {{-- Content --}}
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-xs font-weight-bold text-{{ $reminder['type'] }} text-uppercase">
                                        {{ $reminder['time'] }}
                                    </span>
                                </div>
                                <div class="text-dark small">
                                    {!! $reminder['message'] !!}
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- 2. Sebaran Prodi (DIPINDAHKAN KESINI) --}}
            {{-- 2. Leaderboard Tilawah Top 10 (MENGGANTIKAN SEBARAN PRODI) --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-crown text-warning mr-1"></i> Top 5 Tilawah
                    </h6>
                    <small class="text-muted">Gabungan Mahasiswi & Pengurus</small>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-borderless table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center" style="width: 10%">#</th>
                                    <th>Nama</th>
                                    <th class="text-right">Capaian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaderboard as $row)
                                <tr>
                                    <td class="align-middle text-center">
                                        @if($loop->iteration == 1)
                                            <div class="badge-rank-1">{{ $loop->iteration }}</div>
                                        @elseif($loop->iteration == 2)
                                            <div class="badge-rank-2">{{ $loop->iteration }}</div>
                                        @elseif($loop->iteration == 3)
                                            <div class="badge-rank-3">{{ $loop->iteration }}</div>
                                        @else
                                            <div class="badge-rank-other">{{ $loop->iteration }}</div>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="font-weight-bold text-dark text-truncate" style="max-width: 150px;">
                                            {{ $row->nama }}
                                        </div>
                                        <small class="text-muted">
                                            @if($row->status == 'Pengurus')
                                                <i class="fas fa-user-shield mr-1"></i> Pengurus
                                            @else
                                                <i class="fas fa-user-graduate mr-1"></i> {{ $row->prodi }} / {{ $row->semester }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="align-middle text-right">
                                        @if($row->jml_khatam > 0)
                                            <span class="badge badge-success">{{ $row->jml_khatam }} Khatam</span>
                                        @endif
                                        <span class="badge badge-primary">{{ $row->sisa_juz }} Juz</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">Belum ada data tilawah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center small bg-white">
                    <a href="{{ route('absensiTilawahMahasiswi') }}">Lihat Selengkapnya &rarr;</a>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Statistik Kehadiran & Muhafidzoh --}}
        <div class="col-xl-8 col-lg-7">
            
            {{-- 1. Statistik Kehadiran per Prodi --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Kehadiran per Prodi</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="myBarChart"></canvas>
                    </div>
                    <hr>
                    <div class="small text-center">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Hadir</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Izin</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Alpha</span>
                    </div>
                </div>
            </div>

            {{-- 2. Statistik Muhafidzoh per Gedung --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Muhafidzoh per Gedung</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        {{-- Canvas ID Baru --}}
                        <canvas id="myGedungChart"></canvas>
                    </div>
                    <hr>
                    <div class="small text-center">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Hadir</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Izin</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Alpha</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End Content Row --}}

@endsection

@push('scripts')
    {{-- 1. Gunakan CDN Chart.js v2.9.4 --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    
    {{-- 2. WAJIB: Tambahkan Plugin Datalabels v0.7.0 (Khusus Chart.js v2) --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0/dist/chartjs-plugin-datalabels.min.js"></script>

    <script>
        // Set font family default
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // --- Chart 1: Statistik Kehadiran per Prodi ---
        var ctx = document.getElementById("myBarChart");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($prodiLabels) !!},
                datasets: [{
                        label: "Hadir",
                        backgroundColor: "#1cc88a",
                        hoverBackgroundColor: "#17a673",
                        borderColor: "#1cc88a",
                        // maxBarThickness: 25, // HAPUS INI BIAR BISA DEMPET
                        data: {!! json_encode($dataHadir) !!},
                    },
                    {
                        label: "Izin",
                        backgroundColor: "#f6c23e",
                        hoverBackgroundColor: "#dda20a",
                        borderColor: "#f6c23e",
                        // maxBarThickness: 25, // HAPUS INI
                        data: {!! json_encode($dataIzin) !!},
                    },
                    {
                        label: "Alpha",
                        backgroundColor: "#e74a3b",
                        hoverBackgroundColor: "#be2617",
                        borderColor: "#e74a3b",
                        // maxBarThickness: 25, // HAPUS INI
                        data: {!! json_encode($dataAlpha) !!},
                    }
                ],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        time: { unit: 'prodi' },
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 20 },
                        // PENGATURAN SUPAYA DEMPET:
                        barPercentage: 1.0, 
                        categoryPercentage: 0.7 
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            suggestedMax: 10, 
                            padding: 10,
                            callback: function(value) { return value; }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: { display: false },
                tooltips: {
                    titleFontColor: '#6e707e',
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15, yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                },
                plugins: {
                    datalabels: {
                        color: '#4e73df', 
                        anchor: 'end',    
                        align: 'top',     
                        font: { weight: 'bold', size: 10 },
                        formatter: function(value, context) {
                            if (value === 0) return null;
                            var index = context.dataIndex; 
                            var datasets = context.chart.data.datasets;
                            var total = 0;
                            datasets.forEach(function(dataset) {
                                total += dataset.data[index];
                            });
                            var percentage = Math.round((value / total) * 100) + '%';
                            return percentage;
                        }
                    }
                }
            }
        });

        // --- Chart 2: Statistik Muhafidzoh per Gedung ---
        var ctxGedung = document.getElementById("myGedungChart");
        var myGedungChart = new Chart(ctxGedung, {
            type: 'bar',
            data: {
                labels: {!! json_encode($gedungLabels) !!}, 
                datasets: [{
                        label: "Hadir",
                        backgroundColor: "#1cc88a",
                        hoverBackgroundColor: "#17a673",
                        borderColor: "#1cc88a",
                        // maxBarThickness: 25, // HAPUS INI
                        data: {!! json_encode($gedungHadir) !!},
                    },
                    {
                        label: "Izin",
                        backgroundColor: "#f6c23e",
                        hoverBackgroundColor: "#dda20a",
                        borderColor: "#f6c23e",
                        // maxBarThickness: 25, // HAPUS INI
                        data: {!! json_encode($gedungIzin) !!},
                    },
                    {
                        label: "Alpha",
                        backgroundColor: "#e74a3b",
                        hoverBackgroundColor: "#be2617",
                        borderColor: "#e74a3b",
                        // maxBarThickness: 25, // HAPUS INI
                        data: {!! json_encode($gedungAlpha) !!},
                    }
                ],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 25, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        gridLines: { display: false, drawBorder: false },
                        ticks: { maxTicksLimit: 20 },
                        // PENGATURAN SUPAYA DEMPET:
                        barPercentage: 1.0, 
                        categoryPercentage: 0.7 
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            suggestedMax: 10,
                            padding: 10,
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: { display: false },
                tooltips: {
                    titleFontColor: '#6e707e',
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15, yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                plugins: {
                    datalabels: {
                        color: '#4e73df', 
                        anchor: 'end',
                        align: 'top',
                        font: { weight: 'bold', size: 10 },
                        formatter: function(value, context) {
                            if (value === 0) return null;
                            var index = context.dataIndex;
                            var datasets = context.chart.data.datasets;
                            var total = 0;
                            datasets.forEach(function(dataset) {
                                total += dataset.data[index];
                            });
                            var percentage = Math.round((value / total) * 100) + '%';
                            return percentage;
                        }
                    }
                }
            }
        });
    </script>
@endpush