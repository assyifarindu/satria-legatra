<div class="modal fade" id="cancel-modal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="cancelModalLabel">
                    Cancel Request Document
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    Apakah Anda yakin ingin membatalkan Request Document berikut?
                </p>

                <div class="alert alert-warning">

                    <strong>
                        {{ $requestDocument->title }}
                    </strong>

                </div>

                <p class="mb-0 text-danger">
                    Request Document yang sudah dibatalkan tidak dapat
                    dilanjutkan ke proses berikutnya.
                </p>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Close

                </button>

                <button type="button" class="btn btn-danger" id="confirm-cancel" data-id="{{ $requestDocument->id }}">

                    <i class="fas fa-times me-1"></i>
                    Yes, Cancel Request

                </button>

            </div>

        </div>

    </div>

</div>
