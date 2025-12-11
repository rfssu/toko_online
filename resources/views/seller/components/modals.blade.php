<input type="checkbox" id="close-modal" class="modal-toggle" />
<div class="modal" id="modal-form-ajax" role="dialog">
    <div class="modal-box w-11/12 max-w-5xl">
        <div class="modal-content"></div>
    </div>
</div>

<input type="checkbox" id="close-modal-delete" class="modal-toggle" />
<div class="modal modal-bottom sm:modal-middle" id="modal-delete-confirm" role="dialog">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Hapus</h3>

        <div class="py-4">
            <p class="text-base">
                Apakah Anda yakin ingin menghapus data
                <span class="font-semibold" data-key="name" data-val="ini">ini</span>?
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <form method="POST" action="">
            @csrf
            @method('DELETE')

            <div class="modal-action">
                <label for="close-modal-delete" class="btn btn-ghost">
                    Batal
                </label>
                <button type="submit" class="btn btn-error">
                    <i class="ri-delete-bin-line mr-2"></i>
                    Hapus
                </button>
            </div>
        </form>
    </div>
    <label class="modal-backdrop" for="close-modal-delete"></label>
</div>