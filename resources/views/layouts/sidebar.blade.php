<!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('sbadmin2/img/logo_brand.png') }}" alt="Logo" style="height: 60px; width: auto;"></div>
                <div class="sidebar-brand-text ">SimaQ</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ $menuAdmin ?? ''}}">
            <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
            </a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                MENU ADMIN
            </div>

            <li class="nav-item {{ $menuAdminUser ?? ''}}">
            <a class="nav-link" href="{{ url('/user') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Data User</span></a>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                MENU UTAMA
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ $menuData ?? ''}}">
                <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Data</span>
                </a>
                <div id="collapsePages" class="collapse " aria-labelledby="headingPages"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Data Screens:</h6>
                        <a class="collapse-item {{ $menuPengurus ?? '' }}" href="{{ route('pengurus') }}">Data Pengurus</a>
                        <a class="collapse-item {{ $menuMahasiswi ?? ''}}"  href="{{ route('mahasiswi') }}"> Data Mahasiswi</a>
                        <a class="collapse-item {{ $menuMuhafidzoh ?? ''}}" href="{{ route('muhafidzoh') }}">Data Muhafizoh</a>
                        <a class="collapse-item {{ $menuDosen ?? ''}}"      href="{{ route('dosen') }}">     Data Dosen Pembimbing</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ $menuAbsensiAnggota ?? '' }}">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-clipboard-list"></i>
        <span>Absensi Anggota</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Kegiatan:</h6>

            <!-- Tahfidz trigger -->
            <a class="collapse-item collapsed {{ $menuAbsensiTahfidz ?? '' }}" href="#" data-toggle="collapse" data-target="#collapseTahfidz"
                aria-expanded="false" aria-controls="collapseTahfidz">
                Tahfidz
            </a>
            <!-- Tahfidz submenu -->
            <div id="collapseTahfidz" class="collapse" aria-labelledby="headingTahfidz" data-parent="#collapseTwo">
    <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item {{ $tahfidzmahasiswi ?? ''}}" href="{{ route('absensiTahfidzMahasiswi') }}">Mahasiswi</a>
        <a class="collapse-item {{ $tahfidzmuhafidzoh ?? '' }}" href="{{ route('absensiTahfidzMuhafidzoh') }}">Muhafidzoh</a>
    </div>  
            </div>

            <!-- Tahsin trigger -->
            <a class="collapse-item collapsed {{ $menuAbsensiTilawah ?? '' }}" href="#" data-toggle="collapse" data-target="#collapseTahsin"
                aria-expanded="false" aria-controls="collapseTahsin">
                Tilawah
            </a>
            <!-- Tilawah submenu -->
            <div id="collapseTahsin" class="collapse" aria-labelledby="headingTahsin" data-parent="#collapseTwo">
    <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item {{ $tilawahmahasiswi ?? '' }}" href="{{ route('absensiTilawahMahasiswi') }}">Mahasiswi</a>
    </div>
            </div>

        </div>
    </div>
</li>


            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item {{ $menuAbsensiPengurus ?? ''}}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-clipboard-list"></i>
                    <span>Absensi Pengurus</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Kegiatan:</h6>
                        
                        <a class="collapse-item" href="{{ route('laporan.index') }}">Absensi Kegiatan</a>
                        <a class="collapse-item {{ $pengurusTilawah ?? ''}}" href="{{ route('pengurusTilawah') }}">Tilawah</a>
                    </div>
                </div>
            </li>



            <li class="nav-item {{ $menuUjian ?? '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUjian"
                aria-expanded="true" aria-controls="collapseUjian">
            <i class="fas fa-fw fa-list"></i>
            <span>Ujian</span>
            </a>
            <div id="collapseUjian" class="collapse" aria-labelledby="headingUjian" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
          <h6 class="collapse-header">Nilai:</h6>

          <!-- Submenu Ujian Tahfidz -->
          <a class="collapse-item collapsed {{ $menuUjianTahfidz ?? ''}}" href="#" data-toggle="collapse" data-target="#collapseUjianTahfidz"
              aria-expanded="false" aria-controls="collapseUjianTahfidz">
              Ujian Tahfidz
          </a>
          <div id="collapseUjianTahfidz" class="collapse" aria-labelledby="headingUjianTahfidz" data-parent="#collapseUjian">
              <div class="bg-white py-2 collapse-inner rounded">
                  <a class="collapse-item {{ $mandiri ?? '' }}" href="{{ route('mandiri') }}">Ujian Mandiri</a>
                  <a class="collapse-item {{ $serentak ?? '' }}" href="{{ route('serentak') }}">Ujian Serentak</a>
                  <a class="collapse-item {{ $remedial ?? '' }}" href="{{ route('remedial') }}">Remedial</a>
              </div>
          </div>
                    <a class="collapse-item {{ $menutahsin ?? ''}}"   href="{{ route('tahsin') }} ">Ujian Tahsin</a>  
      </div>
  </div>
</li>




            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                ARSIP
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item {{ $menuDokumentasi ?? ''}}">
                <a class="nav-link" href="{{ route('dokumentasi') }}">
                    <i class="fas fa-fw fa-link"></i>
                    <span>Documentation</span></a>
            </li>

            
        
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->