@extends('layouts.master')

@section('title')
    Create QR Document
@endsection

@section('css')
    <style>
        .form-control:read-only {
            background-color: rgba(234, 234, 234, 0.603);
        }

        #reupload {
            background-color: white;
        }

        #canvas-container {
            position: relative;
            width: max-content;
            height: max-content;
            padding: 0px;
        }

        .draggable {
            display: none;
            width: 87px;
            height: 87px;
            background-color: #9465ab;
            touch-action: none;
            user-select: none;
            text-align: center;
            padding: 30px 0;
            color: white;
            position: absolute;
        }

        #pdf-canvas {
            border: solid 1px rgb(164, 160, 160);
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('qr-document.index') }}">QR Document</a></li>
                                <li class="breadcrumb-item active">Create New</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Create QR Document</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Create QR Document</h4>
                            <form action="{{ route('qr-document.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="no_ducument">No Document <span class="text-danger">*</span></label>
                                        <select class="form-select" name="no_document" id="no_document">
                                            <option selected disabled>-- Select Document Number --</option>
                                            @foreach ($documentNumbers as $item)
                                                <option value="{{ $item->id }}">{{ $item->document_number }}</option>
                                            @endforeach
                                        </select>
                                        @error('no_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="date_document">Date Document <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('date_document') is-invalid @enderror"
                                            id="date_document" name="date_document" value="{{ old('date_document') }}"
                                            required>
                                        @error('date_document')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="description">Description <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                            cols="15" rows="5" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="receipent">Receipent <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('receipent') is-invalid @enderror"
                                            id="receipent" name="receipent" value="{{ old('receipent') }}" required>
                                        @error('receipent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="no_materai">No Materai <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('no_materai') is-invalid @enderror"
                                            id="no_materai" name="no_materai" value="{{ old('no_materai') }}" required>
                                        @error('no_materai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <label for="user_sign">Document Sign By</label>
                                        <input type="text" class="form-control @error('user_sign') is-invalid @enderror"
                                            id="user_sign" name="user_sign" value="{{ old('user_sign') }}" required>
                                        @error('user_sign')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label for="user_sign">Reupload Document</label>
                                    <div class="row align-items-center">
                                        <div class="col-lg-9">
                                            <input type="file" id="reupload" name="reupload" class="form-control">
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" id="resetButton" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 mt-3">
                                        <input hidden type="text" id="file" name="file">
                                        <div class="" id="canvas-container">
                                            <div class="draggable"> QR </div>
                                            <canvas id="pdf-canvas"> ~ PDF ~</canvas>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="stampX" name="stampX">
                                <input type="hidden" id="stampY" name="stampY">
                                <input type="hidden" id="canvasHeight" name="canvasHeight">
                                <input type="hidden" id="canvasWidth" name="canvasWidth">
                                <input type="hidden" id="pageNumber" name="pageNumber" value="-" readonly
                                    placeholder="Page number">

                                <div class="text-end mt-2">
                                    <button class="btn btn-primary waves-effect waves-light"
                                        type="submit">Submit</button>
                                    <a href="javascript:history.back()" class="btn btn-secondary waves-effect">Cancel</a>
                                </div>
                            </form>
                            <span class="text-danger">* Required field</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="module" src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.269/pdf.min.mjs'></script>
    <script type="module" src='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.0.269/pdf.worker.min.mjs'></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs@1.10.20/dist/interact.min.js"></script>
    <script>
        var pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d');

        document.querySelector("#reupload").addEventListener("change", async function(e) {
            var file = e.target.files[0];
            if (file.type != "application/pdf") {
                alert(file.name + " is not a pdf file.");
                return;
            }

            var fileReader = new FileReader();

            fileReader.onload = async function() {
                var typedarray = new Uint8Array(this.result);

                const loadingTask = pdfjsLib.getDocument(typedarray);
                loadingTask.promise.then(pdf => {
                    pdfDoc = pdf;
                    pageNum = pdfDoc.numPages;

                    document.getElementById('pageNumber').value = pageNum;
                    renderPage(pageNum);

                    document.getElementById('pdf-canvas').style.display = 'block';
                    document.getElementsByClassName('draggable')[0].style.display = 'block';
                });
            };

            fileReader.readAsArrayBuffer(file);

            // Display the draggable QR code and canvas
            document.getElementsByClassName('draggable')[0].style.display = 'block';
            document.getElementById('pdf-canvas').style.display = 'block';
        });

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({
                    scale: scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                document.getElementById('canvasHeight').value = viewport.height;
                document.getElementById('canvasWidth').value = viewport.width;

                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function loadPdf(url) {
            const loadingTask = pdfjsLib.getDocument(url);
            loadingTask.promise.then(pdf => {
                pdfDoc = pdf;
                pageNum = pdfDoc.numPages;
                document.getElementById('pageNumber').value = pageNum;
                renderPage(pageNum);
                document.getElementById('pdf-canvas').style.display = 'block';
            });
        }

        $('#resetButton').click(function() {
            // Reset all form fields
            $('form').trigger('reset');

            // Clear the file input manually
            $('#reupload').val('');

            // Hide the canvas and draggable element
            $('#pdf-canvas').hide();
            $('.draggable').hide();

            // Reset the readonly fields and remove their readonly attribute
            $("#receipent, #user_sign, #date_document").prop('readonly', false);

            // Clear the hidden fields
            $('#file').val('');
            $('#stampX').val('');
            $('#stampY').val('');
            $('#canvasHeight').val('');
            $('#canvasWidth').val('');
            $('#pageNumber').val('-');


            $('#no_document option:selected').prop('selected', false);
            $('#no_document option:first-child').prop('disabled', true);
            $('#no_document option:first-child').prop('selected', true);
            $('#no_document').trigger('change');

        });

        $(document).ready(function() {
            $('#no_document').select2();
            $('#no_document').on('change', function() {
                var idGenerateNumber = this.value;
                console.log('idGenerateNumber', idGenerateNumber);
                if (idGenerateNumber) {
                    console.log('test');
                    $.ajax({
                        // url: '/qr-document/document/' + idGenerateNumber,
                        url: '{{ url('/qr-document/document/') }}/' + idGenerateNumber,
                        type: 'GET',
                        success: function(data) {
                            console.log(data);
                            $("#receipent").val(data.receipent);
                            $("#user_sign").val(data.sign_by);

                            let date = new Date(data.created_at);
                            let formattedDate = date.toISOString().substring(0, 10);

                            $("#date_document").val(formattedDate);
                            $("#receipent, #user_sign, #date_document").prop('readonly', true);

                            if (data.file) {
                                $("#file").val(data.file);
                                var link = "{{ asset('storage') }}" + data.file;
                                loadPdf(link);
                                document.getElementsByClassName('draggable')[0].style.display =
                                    'block';
                            }
                        }
                    });
                }
            });
        });

        const position = {
            x: 0,
            y: 0
        };
        interact('.draggable').draggable({
            listeners: {
                move(event) {
                    const containerRect = document.getElementById('canvas-container').getBoundingClientRect();
                    const draggableRect = event.target.getBoundingClientRect();
                    const canvasRect = canvas.getBoundingClientRect();

                    position.x += event.dx;
                    position.y += event.dy;

                    let left = position.x;
                    let top = position.y;

                    // Constrain the draggable element within the canvas
                    left = Math.max(0, Math.min(left, canvasRect.width - draggableRect.width));
                    top = Math.max(0, Math.min(top, canvasRect.height - draggableRect.height));

                    event.target.style.transform = `translate(${left}px, ${top}px)`;

                    // Store the position
                    document.getElementById('stampX').value = left;
                    document.getElementById('stampY').value = top;
                },
                end(event) {
                    const left = position.x;
                    const top = position.y;

                    document.getElementById('stampX').value = left;
                    document.getElementById('stampY').value = top;
                }
            }
        });
    </script>
@endsection
