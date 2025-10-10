<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="exampleModalLabel">Hapus {{ $title }}?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" class=" text-white">&times;</span>
        </button>
      </div>
      <div class="modal-body text-left">
        <div class="row">
            <div class="col-12">
                Foto : {{ $item->foto }}
            </div>
            <div class="col-12">
                Nama : {{ $item->nama }}
            </div>
            <div class="col-12">
                Email : {{ $item->email }}
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i>
            Tutup</button>
        <a href="{{ route('pengurusDestroy', $item->id_pengurus) }}" type="button" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
            Hapus</a>
      </div>
    </div>
  </div>
</div>
