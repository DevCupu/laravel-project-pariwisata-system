<div id="confirmation-modal" class="modal" role="dialog" aria-modal="true" aria-labelledby="confirmation-modal-title">
    <div class="modal-box max-w-md text-center">
        <div class="flex flex-col items-center">
            <span class="flex h-14 w-14 items-center justify-center rounded-full bg-error/10">
                <i class="fas fa-triangle-exclamation text-2xl text-error"></i>
            </span>
            <h3 id="confirmation-modal-title" class="mt-4 text-lg font-bold">
                Konfirmasi Tindakan
            </h3>
            <p data-confirm-message class="mt-2 text-sm text-base-content/70">
                Apakah Anda yakin ingin melakukan tindakan ini?
            </p>
        </div>
        <div class="modal-action mt-6 flex-col sm:flex-row gap-2.5">
            <button type="button" data-confirm-cancel class="btn btn-outline flex-1">
                Batal
            </button>
            <button type="button" data-confirm-accept class="btn btn-error flex-1">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
    <div class="modal-backdrop bg-base-content/40 backdrop-blur-sm"></div>
</div>
