<div class="modal fade" id="request-to-revision-flr-committee-modal" tabindex="-1"
    aria-labelledby="requestToRevisionFLRCommitteeModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <form id="request-to-revision-flr-committee-form" data-id="{{ $requestDocument->id }}"
            enctype="multipart/form-data">

            @csrf

            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header">

                    <h5 class="modal-title" id="requestToRevisionFLRCommitteeModalLabel">

                        Request to Revision Form Legal Review

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>


                {{-- BODY --}}
                <div class="modal-body">

                    <p>
                        Silakan masukkan alasan dan attachment untuk
                        Request to Revision.
                    </p>


                    {{-- REQUEST DOCUMENT --}}
                    <div class="alert alert-warning">

                        <strong>
                            {{ $requestDocument->title }}
                        </strong>

                    </div>


                    {{-- REASON --}}

                    <div class="mb-3">

                        <label for="revision-remark" class="form-label fw-bold">

                            Reason

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <textarea name="remark" id="revision-remark" class="form-control" rows="4"
                            placeholder="Masukkan alasan request to revision..."></textarea>


                        <div id="revision-remark-error" class="invalid-feedback">
                        </div>

                    </div>

                    {{-- ATTACHMENT --}}

                    <div class="mb-3">

                        <label for="revision-attachment" class="form-label fw-bold">

                            Attachment

                            <span class="text-danger">
                                *
                            </span>

                        </label>


                        <input type="file" name="attachment" id="revision-attachment" class="form-control"
                            accept=".pdf">


                        <div class="form-text">

                            Attachment file harus berformat PDF dengan
                            maksimal ukuran 10 MB.

                        </div>


                        <div id="revision-attachment-error" class="invalid-feedback">
                        </div>

                    </div>


                    <div class="alert alert-info mb-0">

                        <i class="mdi mdi-information-outline me-1"></i>

                        Request Document akan dikembalikan ke Legal Drafting
                        untuk dilakukan revisi.

                    </div>

                </div>


                {{-- FOOTER --}}
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Close

                    </button>


                    <button type="submit" class="btn btn-primary" id="confirm-request-revision-flr-committee">

                        <i class="fas fa-save me-1"></i>

                        Submit

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
