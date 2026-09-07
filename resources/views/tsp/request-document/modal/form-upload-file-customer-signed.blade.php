<div class="modal fade" id="upload-file-customer-signed-modal" tabindex="-1"
    aria-labelledby="uploadFileCustomerSignedModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form id="upload-file-customer-signed-form" data-id="{{ $requestDocument->id }}" enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header">

                    <h5 class="modal-title" id="uploadFileCustomerSignedModalLabel">

                        Upload Customer Signed Document

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body">

                    <p>
                        Silakan unggah dokumen yang telah ditandatangani oleh Customer.
                    </p>


                    {{-- REQUEST DOCUMENT --}}
                    <div class="alert alert-warning">

                        <strong>
                            {{ $requestDocument->title }}
                        </strong>

                    </div>


                    {{-- FINAL_DOCUMENT --}}

                    <div class="mb-3">

                        <label for="attachment" class="form-label fw-bold">

                            Customer Signed Document

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf">


                        <div class="form-text">

                            Attachment file harus berformat PDF dengan
                            maksimal ukuran 10 MB.

                        </div>


                        <div id="attachment-error" class="invalid-feedback">
                        </div>

                    </div>


                    {{-- <div class="alert alert-info mb-0">

                        <i class="mdi mdi-information-outline me-1"></i>

                        Customer Signed Document akan dikirim ke Admin Legal untuk diproses lebih lanjut.

                    </div> --}}

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>


                    <button type="submit" class="btn btn-primary" id="confirm-upload-file-customer-signed">

                        <i class="fas fa-save me-1"></i>

                        Submit

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
