<div class="modal fade" id="history-modal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="historyModalLabel">

                    <i class="mdi mdi-book-clock-outline"></i>

                    History Process

                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <div class="modal-body">

                <div class="mb-3">

                    <strong>Request Document:</strong>

                    {{ $requestDocument->document_number ? $requestDocument->document_number . " - " . $requestDocument->title : $requestDocument->title }}

                </div>

                <table id="history-table" class="table table-bordered table-striped nowrap w-100">

                    <thead>

                        <tr>

                            <th width="5%">#</th>

                            <th>Date</th>

                            <th>Action</th>

                            <th>Action By</th>

                        </tr>

                    </thead>

                    <tbody></tbody>

                </table>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>
