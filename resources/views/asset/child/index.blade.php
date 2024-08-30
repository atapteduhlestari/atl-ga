@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Document')
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">All Documents</h1>
        <!-- Button trigger modal -->
        <div class="d-flex">
            <div class="my-3 flex-grow-1">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addNewRecord">
                    Add <i class="fas fa-plus-circle"></i>
                </button>
            </div>

            <div class="my-3">
                <a title="refresh data" class="btn btn-outline-success" href="/asset-child" type="button">
                    <i class="fas fa-sync-alt"></i>
                </a>
                <button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapseSearch"
                    aria-expanded="false" aria-controls="collapseSearch">
                    Filter Search
                </button>
            </div>
        </div>

        <div class="collapse" id="collapseSearch">
            <div class="card card-body mt-3">
                <h6 class="mb-3 font-weight-bold text-primary">Search</h6>
                <form action="/asset-child" method="get">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="group_search_id">Type</label>
                            <select class="form-control form-control-sm @error('group_search_id') is-invalid @enderror"
                                name="group_search_id" id="group_search_id">
                                <option value="" disabled selected></option>
                                @foreach ($documentGroup as $group)
                                    <option value="{{ $group->id }}"
                                        {{ request('group_search_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->document_group_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @can('superadmin')
                            <div class="col-md-6 mb-3">
                                <label for="sbu_search_id">SBU</label>
                                <select class="form-control form-control-sm @error('sbu_search_id') is-invalid @enderror"
                                    id="sbu_search_id" name="sbu_search_id">
                                    <option value=""></option>
                                    @foreach ($SBUs as $sb)
                                        <option value="{{ $sb->id }}"
                                            {{ request('sbu_search_id') == $sb->id ? 'selected' : '' }}>
                                            {{ $sb->sbu_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endcan

                        <div class="col-md-6">
                            <label for="search_date_before">Date</label>
                            <div class="form-group d-flex">
                                <input type="date" class="form-control form-control-sm" id="search_date_before"
                                    name="search_date_before" value="{{ request('search_date_before') }}">
                            </div>
                            <div class="form-group d-flex">
                                <input type="date" class="form-control form-control-sm" id="search_date_after"
                                    name="search_date_after" value="{{ request('search_date_after') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <button type="submit" class="btn btn-outline-primary rounded text-xs">
                                Find <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Table Data</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Code Acc</th>
                                <th>Doc Name</th>
                                <th>Asset</th>
                                <th>SBU</th>
                                <th>SDB</th>
                                <th>File</th>
                                <th class="text-center" width=25%>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($children as $child)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $child->document->document_group_name ?? '-' }}</td>
                                    <td>{{ $child->doc_code }}</td>
                                    <td>{{ $child->doc_name }}</td>
                                    <td>
                                        @if ($child->parent)
                                            <a href="/asset-parent/docs/{{ $child->asset_id }}">
                                                {{ $child->parent->asset_name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $child->sbu->sbu_name ?? '' }}</td>
                                    <td>{{ $child->sdb->sdb_name ?? '' }}</td>
                                    <td>
                                        @if ($child->file)
                                            <a title="download file" href="/asset-child/download/{{ $child->id }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <div>
                                                <a title="Asset Detail" href="/asset-child/{{ $child->id }}"
                                                    class="btn btn-outline-dark btn-sm"><i
                                                        class="fas fa-search-plus"></i></a>
                                            </div>
                                            <form action="/trn-renewal/create">
                                                <input type="hidden" name="id" value="{{ $child->id }}" readonly>
                                                <button type="submit" class="btn btn-outline-dark btn-sm">
                                                    Add Renewal
                                                </button>
                                            </form>
                                            <div>
                                                <a title="Edit Data" href="/asset-child/{{ $child->id }}/edit"
                                                    class="btn btn-outline-dark text-xs">Edit</a>
                                            </div>
                                            <div>
                                                <form action="/asset-child/{{ $child->id }}" method="POST"
                                                    id="deleteForm">
                                                    @csrf
                                                    @method('delete')
                                                    <button title="Delete Data" class="btn btn-outline-danger text-xs"
                                                        onclick="return false" id="deleteDocButton"
                                                        data-id="{{ $child->id }}">
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

    <!-- Modal -->
    <div class="modal fade" id="addNewRecord" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="addNewRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-gradient-dark">
                    <h5 class="modal-title text-white" id="addNewRecordLabel">Form - Add New Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/asset-child" method="POST" id="formAdd" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="doc_name">Document Name</label>
                                    <input name="doc_name" id="doc_name" type="text"
                                        class="form-control @error('doc_name') is-invalid @enderror"
                                        value="{{ old('doc_name') }}" autocomplete="off" autofocus>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_id">Assets</label>
                                    <select class="form-control @error('asset_id') is-invalid @enderror" name="asset_id"
                                        id="asset_id">
                                        <option value=""></option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}"
                                                {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                                {{ $asset->asset_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="doc_code">Code Acc</label>
                                    <input name="doc_code" id="doc_code" type="text"
                                        class="form-control @error('doc_code') is-invalid @enderror"
                                        value="{{ old('doc_code') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="document_id">Type</label>
                                <select class="form-control @error('document_id') is-invalid @enderror" name="document_id"
                                    id="document_id">
                                    <option value=""></option>
                                    @foreach ($documentGroup as $group)
                                        <option value="{{ $group->id }}"
                                            {{ old('document_id') == $group->id ? 'selected' : '' }}>
                                            {{ $group->document_group_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sdb_id">SDB</label>
                                <select class="form-control @error('sdb_id') is-invalid @enderror" name="sdb_id"
                                    id="sdb_id">
                                    <option value=""></option>
                                    @foreach ($SDBs as $sdb)
                                        <option value="{{ $sdb->id }}"
                                            {{ old('sdb_id') == $sdb->id ? 'selected' : '' }}>
                                            {{ $sdb->sdb_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @can('superadmin')
                                <div class="col-md-6 mb-3">
                                    <label for="sbu_id">SBU</label>
                                    <select class="form-control @error('sbu_id') is-invalid @enderror" name="sbu_id"
                                        id="sbu_id">
                                        <option value=""></option>
                                        @foreach ($SBUs as $sbu)
                                            <option value="{{ $sbu->id }}"
                                                {{ old('sbu_id') == $sbu->id ? 'selected' : '' }}>
                                                {{ $sbu->sbu_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endcan
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="desc">Description</label>
                                    <textarea class="form-control  @error('desc') is-invalid @enderror" name="desc" id="desc" cols="30"
                                        rows="5">{{ old('desc') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="">File</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input  @error('file') is-invalid @enderror"
                                        name="file" id="fileInput">
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <label class="custom-file-label" for="file">Choose file</label>
                                </div>

                            </div>
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
    <script src="/assets/template/vendor/selectize/selectize.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $('#fileInput').on('change', function(e) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });

        let btnSubmit = $('#btnSubmit'),
            form = $('#formAdd'),
            formDelete = $('form#deleteForm');

        $("#asset_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#sbu_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#sbu_search_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#group_search_id").selectize({
            create: false,
            sortField: "text",
        });


        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });

        $(document).on('click', '#deleteDocButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/asset-child/${id}`)
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
