@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Edit Document')
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="d-flex align-items-center mb-3">
            <div class="flex-grow-1">
                <h1 class="h3 mb-2 text-gray-800">Document / Edit /
                    {{ $child->parent ? $child->parent->asset_name . ' / ' : ' ' }}
                    {{ $child->doc_name }}
                </h1>
            </div>
            <a href="/asset-child" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            {{-- <a href="/asset-parent/docs/{{ $asset->id }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> {{ $asset->asset_name }}
            </a> --}}
        </div>
        <!-- Page Heading -->
        <div class="my-4">
            <form id="formAdd" action="/asset-child/{{ $child->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="doc_name">Document Name</label>
                            <input name="doc_name" id="doc_name" type="text"
                                class="form-control @error('doc_name') is-invalid @enderror"
                                value="{{ old('doc_name', $child->doc_name) }}" autocomplete="off" autofocus>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Asset</label>
                            <input type="text" class="form-control not-allowed"
                                value="{{ $child->parent->asset_name ?? null }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="doc_code">Code Acc</label>
                            <input name="doc_code" id="doc_code" type="text"
                                class="form-control @error('doc_code') is-invalid @enderror"
                                value="{{ old('doc_code', $child->doc_code) }}">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="document_id">Type</label>
                        <select class="form-control @error('document_id') is-invalid @enderror" name="document_id"
                            id="document_id">
                            <option value=""></option>
                            @foreach ($documentGroup as $group)
                                <option value="{{ $group->id }}"
                                    {{ old('document_id', $child->document_id) == $group->id ? 'selected' : '' }}>
                                    {{ $group->document_group_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sdb_id">SDB</label>
                            <select class="form-control @error('sdb_id') is-invalid @enderror" name="sdb_id"
                                id="sdb_id">
                                <option value=""></option>
                                @foreach ($SDBs as $sdb)
                                    <option value="{{ $sdb->id }}"
                                        {{ old('sdb_id', $child->sdb_id) == $sdb->id ? 'selected' : '' }}>
                                        {{ $sdb->sdb_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @can('superadmin')
                        <div class="col-md-6 mb-3">
                            <label for="sbu_id">SBU</label>
                            <select class="form-control @error('sbu_id') is-invalid @enderror" name="sbu_id" id="sbu_id">
                                <option value=""></option>
                                @foreach ($SBUs as $sbu)
                                    <option value="{{ $sbu->id }}"
                                        {{ old('sbu_id', $child->sbu_id) == $sbu->id ? 'selected' : '' }}>
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
                                rows="5">{{ old('desc', $child->desc) }}</textarea>
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
                            <small>{{ $child->getFileName() }}</small>
                        </div>
                    </div>
                </div>
                <button type="button" id="btnSubmit" class="btn btn-sm btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/template/vendor/selectize/selectize.js"></script>
    <script>
        let btnSubmit = $('#btnSubmit'),
            form = $('#formAdd');

        $('#fileInput').on('change', function(e) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });

        $("#sbu_id").selectize({
            create: false,
            sortField: "text",
        });

        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });
    </script>
@endpush
