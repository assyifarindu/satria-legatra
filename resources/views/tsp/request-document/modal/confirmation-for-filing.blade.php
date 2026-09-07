<div class="modal fade" id="confirm-filing-modal" tabindex="-1" aria-labelledby="confirmFilingModalLabel"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="cancelModalLabel">
                    Filing Request Document
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <p>
                    Apakah Anda yakin ingin melakukan Filing Request Document berikut?
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

                <button type="button" class="btn btn-success" id="confirm-filing" data-id="{{ $requestDocument->id }}">

                    <i class="fas fa-check me-1"></i>
                    Yes, Filing Request

                </button>

            </div>

        </div>

    </div>

</div>
