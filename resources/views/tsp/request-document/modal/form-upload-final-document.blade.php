<div class="modal fade" id="upload-final-document-modal" tabindex="-1" aria-labelledby="uploadFinalDocumentModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form id="upload-final-document-form" data-id="{{ $requestDocument->id }}" enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header">

                    <h5 class="modal-title" id="uploadFinalDocumentModalLabel">

                        Upload Final Document

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body">

                    <p>
                        Silakan unggah dokumen final.
                    </p>


                    {{-- REQUEST DOCUMENT --}}
                    <div class="alert alert-warning">

                        <strong>
                            {{ $requestDocument->title }}
                        </strong>

                    </div>


                    {{-- FINAL_DOCUMENT --}}

                    <div class="mb-3">

                        <label for="final_document" class="form-label fw-bold">

                            Final Document

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="file" name="final_document" id="final_document" class="form-control"
                            accept=".pdf">


                        <div class="form-text">

                            Attachment file harus berformat PDF dengan
                            maksimal ukuran 10 MB.

                        </div>


                        <div id="final-document-error" class="invalid-feedback">
                        </div>

                    </div>


                    <div class="alert alert-info mb-0">

                        <i class="mdi mdi-information-outline me-1"></i>

                        Final Document akan dikirim ke Admin Legal untuk diproses lebih lanjut.

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>


                    <button type="submit" class="btn btn-primary" id="confirm-upload-final-document">

                        <i class="fas fa-save me-1"></i>

                        Submit

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
