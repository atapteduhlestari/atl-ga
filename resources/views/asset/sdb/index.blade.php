@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('title', 'GA | SDB')
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">SDB</h1>

        <div class="my-4">
            <!-- Button trigger modal -->
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addNewRecord">
                Add <i class="fas fa-plus-circle"></i>
            </button>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List record</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>SDB</th>
                                <th>Purchase Date</th>
                                <th>Purchase Value</th>
                                {{-- <th>Due Date</th> --}}
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($SDBs as $sdb)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $sdb->sdb_name }}</td>
                                    <td>{{ $sdb->pcs_date ? createDate($sdb->pcs_date)->format('d M Y') : '' }}</td>
                                    <td>{{ rupiah($sdb->pcs_value) }}</td>
                                    {{-- <td>{{ $sdb->due_date ? createDate($sdb->due_date)->format('d M Y') : '' }}</td> --}}
                                    <td>
                                        <div class="d-flex justify-content-around">

                                            <div>
                                                <a title="SDB Detail" href="/sdb/{{ $sdb->id }}"
                                                    class="btn btn-outline-dark btn-sm">Detail</a>
                                            </div>
                                            <div>
                                                <a title="Edit Data" href="/sdb/{{ $sdb->id }}/edit"
                                                    class="btn btn-outline-dark btn-sm">Edit</a>
                                            </div>
                                            <div>
                                                <form action="/sdb/{{ $sdb->id }}" method="post" id="deleteForm">
                                                    @csrf
                                                    @method('delete')
                                                    <button title="Delete Data" class="btn btn-outline-danger btn-sm"
                                                        onclick="return false" id="deleteButton"
                                                        data-id="{{ $sdb->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
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

    <div class="modal fade" id="addNewRecord" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="addNewRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-gradient-dark">
                    <h5 class="modal-title text-white" id="addNewRecordLabel">Form - Add SDB</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/sdb" method="POST" id="formAdd" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sdb_name">SDB Name</label>
                                <input type="text" class="form-control @error('sdb_name') is-invalid @enderror"
                                    name="sdb_name" id="sdb_name" value="{{ old('sdb_name') }}" autocomplete="off"
                                    autofocus>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pcs_date">Purchase Date</label>
                                <input type="date" class="form-control @error('pcs_date') is-invalid @enderror"
                                    name="pcs_date" id="pcs_date" value="{{ old('pcs_date') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pcs_value">Purchase Value</label>
                                <input type="text" class="form-control currency @error('pcs_value') is-invalid @enderror"
                                    name="pcs_value" id="pcs_value" value="{{ old('pcs_value') }}">
                            </div>
                            {{-- <div class="col-md-6 mb-3">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                    name="due_date" id="due_date" value="{{ old('due_date') }}">
                            </div> --}}
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/js/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $('.currency').mask('000.000.000.000', {
            reverse: true
        });

        let btnSubmit = $('#btnSubmit'),
            form = $('#formAdd'),
            formDelete = $('#deleteForm');

        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });

        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/sdb/${id}`)
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
    </script>

    @if ($errors->any())
        <script>
            $('#addNewRecord').modal('show');
        </script>
    @endif
@endpush
