<div class="modal fade" id="verify-by-user-modal" tabindex="-1" aria-labelledby="verifyByUserModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="cancelModalLabel">
                    Verify Request Document
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    Apakah Anda yakin ingin memverifikasi Request Document berikut?
                </p>

                <div class="alert alert-warning">

                    <strong>
                        {{ $requestDocument->title }}
                    </strong>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Close

                </button>

                <button type="button" class="btn btn-success" id="confirm-verify" data-id="{{ $requestDocument->id }}">

                    <i class="fas fa-check me-1"></i>
                    Yes, Verify Request

                </button>

            </div>

        </div>

    </div>

</div>
