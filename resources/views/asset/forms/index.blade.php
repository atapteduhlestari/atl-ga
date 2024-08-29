@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('title', 'FORM ISO')
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">Form Template</h1>
        <div class="my-4">
            <form action="/form" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-6">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('url') is-invalid @enderror"
                                name="url" id="urlInput">
                            <label class="custom-file-label" for="url">Add Form</label>
                        </div>
                        @error('url')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Table Data</h6>
            </div>
            <div class="card-body">
                <div class="">
                    <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $file)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><a title="download" href="/form/{{ $file['basename'] }}">{{ $file['filename'] }}</a>
                                    </td>
                                    <td>
                                        <div>
                                            <form action="/form/{{ $file['basename'] }}" method="post" id="deleteForm">
                                                @csrf
                                                @method('delete')
                                                <button title="Delete Data" class="btn btn-outline-danger text-xs"
                                                    onclick="return false" id="deleteButton"
                                                    data-id="{{ $file['basename'] }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        })

        let formDelete = $('#deleteForm');

        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/form/${id}`)
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    formDelete.submit();
                }
            })
        });

        $('#urlInput').on('change', function(e) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        })
    </script>

    @if ($errors->any())
        <script>
            $('#addNewRecord').modal('show');
        </script>
    @endif
@endpush
