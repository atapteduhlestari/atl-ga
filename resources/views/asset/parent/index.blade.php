@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('title', 'GA | Asset')
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Assets</h1>

        <div class="my-4">
            <div class="mb-3">
                <h6 class="text-muted">Add new record</h6>
            </div>
            <form action="/asset-parent" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input name="asset_name" id="asset_name" type="text"
                                class="form-control @error('asset_name') is-invalid @enderror" placeholder="Asset Name"
                                value="{{ old('asset_name') }}" autocomplete="off" autofocus>
                            @error('asset_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control @error('asset_group_id') is-invalid @enderror" name="asset_group_id"
                                id="asset_group_id">
                                <option value="">-Select group-</option>
                                @foreach ($assetGroup as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('asset_group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->asset_group_name }}</option>
                                @endforeach
                            </select>
                            @error('asset_group_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List assets</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Group</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assets as $parent)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $parent->asset_name }}</td>
                                    <td>{{ $parent->group->asset_group_name }}</td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <div>
                                                <a title="Asset Documents" href="/asset-parent/docs/{{ $parent->id }}"
                                                    class="btn btn-outline-dark text-xs">Documents</a>
                                            </div>
                                            <div>
                                                <a title="Edit Data" href="/asset-parent/{{ $parent->id }}/edit"
                                                    class="btn btn-outline-dark text-xs">Edit</a>
                                            </div>
                                            <div>
                                                <form action="/asset-parent/{{ $parent->id }}" method="post"
                                                    id="deleteForm">
                                                    @csrf
                                                    @method('delete')
                                                    <button title="Delete Data" class="btn btn-outline-danger text-xs"
                                                        onclick="return false" id="deleteButton"
                                                        data-id="{{ $parent->id }}">
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
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        let formDelete = $('form#deleteForm');

        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/asset-parent/${id}`)
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
@endpush
