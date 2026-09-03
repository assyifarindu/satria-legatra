<div class="modal fade" id="delete-file-modal" tabindex="-1" aria-labelledby="deleteFileModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="deleteFileModalLabel">
                    Delete File Confirmation
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    Apakah Anda yakin ingin menghapus file berikut?
                </p>

                <div class="alert alert-warning">

                    <strong id="file-name-to-delete">
                    </strong>

                </div>

                <p class="mb-0 text-danger">
                    File yang sudah dihapus tidak dapat dikembalikan.
                </p>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Close

                </button>

                <button type="button" class="btn btn-danger" id="confirm-delete-file">

                    <i class="fas fa-times me-1"></i>
                    Yes, Delete File

                </button>

            </div>

        </div>

    </div>

</div>
