<div class="modal fade" id="decline-modal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <form id="decline-form" data-id="{{ $requestDocument->id }}">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="declineModalLabel">
                        Decline Request Document
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>

                <div class="modal-body">

                    <p>
                        Apakah Anda yakin ingin menolak Request Document berikut?
                    </p>

                    <div class="alert alert-warning">

                        <strong>
                            {{ $requestDocument->title }}
                        </strong>

                    </div>

                    <div class="mb-3">

                        <label for="remark" class="form-label fw-bold">
                            Reason
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="remark" id="remark" class="form-control" rows="4" placeholder="Masukkan alasan penolakan..."></textarea>

                        <div id="remark-error" class="invalid-feedback">
                        </div>

                    </div>

                    <p class="mb-0 text-danger">
                        Request Document yang ditolak tidak dapat
                        dilanjutkan ke proses berikutnya.
                    </p>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>

                    <button type="submit" class="btn btn-danger" id="confirm-decline">

                        <i class="fas fa-times me-1"></i>
                        Yes, Decline Request

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
