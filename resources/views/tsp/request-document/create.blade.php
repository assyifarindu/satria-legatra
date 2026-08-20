@extends('layouts.tsp_master')

@section('title')
    Request Document |
@endsection

@section('css')
@endsection

@section('content')
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                        </div>
                        <h4 class="page-title">Request Document</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Form Create Request Document</h4>
                            <br><br>
                            <form id="form-request-document" class="d-flex flex-column gap-2">
                                @csrf
                                <div>
                                    <label for="title" class="fw-bold d-block">Title </label>
                                    <input type="text" name="title" class="form-control draft-required" placeholder="Title">
                                </div>
                                <div class="col-12">
                                    <label for="customer_name" class="fw-bold d-block">Customer Name </label>
                                    <select name="customer_id" class="form-select"></select>
                                </div>
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" name="action" value="draft" class="btn btn-secondary">
                                        Save as Draft
                                    </button>
                                    <button type="submit" name="action" value="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
        </div> <!-- container -->
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
           $('#form-request-document').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                
                // Deteksi tombol mana yang diklik
                let clickedButton = e.originalEvent.submitter;
                let actionType = $(clickedButton).val(); // Berisi 'draft' atau 'submit'

                // Tambahkan status ke dalam FormData yang dikirim ke controller
                formData.append('action_type', actionType);

                $.ajax({
                    url: "{{ url('tsp/request-document') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        // Disable tombol agar tidak tertekan dua kali
                        $('button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        alert(response.message);
                        
                        // Reset form & reload DataTable
                        $('#form-request-document')[0].reset();
                        $('#request-document-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            console.log(xhr.responseJSON.errors);
                        } else {
                            alert('Terjadi kesalahan.');
                        }
                    },
                    complete: function() {
                        // Aktifkan kembali tombol
                        $('button[type="submit"]').prop('disabled', false);
                    }
                });
            });

            $("select[name='customer_id']").select2({
                placeholder: "Select a customer",
                width: '100%',
                ajax: {
                    url: "{{ url('tsp/customers') }}",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response.data.map(function(customer) {
                                return {
                                    id: customer.id,
                                    text: customer.name
                                };
                            })
                        };
                    }
                }
            });

            $("select[name='customer_id']").on('select2:select', function(e) {
                var selectedCustomerId = e.params.data.id;
                $.ajax({
                    url: `{{ url('tsp/customers/${selectedCustomerId}') }}`,
                    type: 'GET',
                    success: function(response) {
                        console.log("Selected Customer Details:", response);
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat mengambil data customer.');
                    }
                })
            });
        })
    </script>
@endsection
