@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Asset ' . $asset->asset_name)
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex mb-3">
            <div class="flex-grow-1">
                <h1 class="h3 mb-2 text-gray-800">
                    Assets /
                    <a class="text-gray-800" href="/asset-group/{{ $asset->asset_group_id }}">
                        {{ $asset->group->asset_group_name }}
                    </a>
                    /
                    {{ $asset->asset_name }}
                </h1>
            </div>
            <a href="/asset-parent" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow mb-4 h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ $asset->asset_name }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md">
                                <a href="/asset-parent/export/{{ $asset->id }}"
                                    class="btn btn-outline-danger btn-sm px-3">
                                    <i class="fas fa-print"></i> Export PDF
                                </a>
                            </div>
                        </div>
                        <div class="row my-3">
                            <div class="col-md">
                                <img class="img-fluid"
                                    src="{{ $asset->image ? route('image.displayImage', class_basename($asset->image)) : asset('/assets/img/empty-img.jpeg') }}">
                                {!! $asset->image ? '' : '<small class="text-danger"><em>please upload an image!</em></small>' !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <form action="/trn-maintenance/create">
                                    <input type="hidden" name="id" value="{{ $asset->id }}" readonly>
                                    <button type="submit" class="btn btn-outline-dark btn-sm btn-block">
                                        <i class="fas fa-tools"></i> New Maintenance
                                    </button>
                                </form>
                            </div>

                            <div class="col-md-4 mb-3">
                                <form action="/appraisal/create">
                                    <input type="hidden" name="asset_id" value="{{ $asset->id }}" readonly>
                                    <button type="submit" class="btn btn-outline-dark btn-sm btn-block">
                                        <i class="fas fa-chart-line"></i> Appraisal
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a title="Edit Data" href="/asset-parent/{{ $asset->id }}/edit"
                                    class="btn btn-info btn-sm btn-block">Edit</a>
                            </div>

                            <div class="col-md-4 mb-3">
                                <form action="/asset-parent/{{ $asset->id }}" method="post" id="deleteForm">
                                    @csrf
                                    @method('delete')
                                    <button title="Delete Data" class="btn btn-danger btn-sm btn-block"
                                        onclick="return false" id="deleteButton" data-id="{{ $asset->id }}">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $asset->asset_name }}</td>
                                </tr>
                                <tr>
                                    <th>Code Acc</th>
                                    <td>{{ $asset->asset_code ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>No. (Pol/Rumah/Seri)</th>
                                    <td>{{ $asset->asset_no }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{ $asset->group->asset_group_name }}</td>
                                </tr>
                                <tr>
                                    <th>SBU</th>
                                    <td>{{ $asset->sbu->sbu_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $asset->location }}</td>
                                </tr>
                                <tr>
                                    <th>Condition</th>
                                    {{-- @php
                                        $foo = 1;
                                    @endphp --}}
                                    <td class="{{ colorCondition($asset->condition) }}">
                                        {{ textCondition($asset->condition) }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        PIC
                                    </th>
                                    <td>{{ $asset->employee->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Date</th>
                                    <td>{{ createDate($asset->pcs_date)->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Purchase Value</th>
                                    <td>{{ rupiah($asset->pcs_value) }}</td>
                                </tr>
                                <tr>
                                    <th>Nilai Buku</th>
                                    <td>{{ rupiah($asset->nilai_buku) }}</td>
                                </tr>
                                <tr>
                                    <th>Appraisal Date</th>
                                    <td>
                                        {{ $asset->appraisals()->exists() ? createDate($asset->appraisals->last()->apr_date)->format('d F Y') : null }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Appraisal Value</th>
                                    <td>{{ rupiah($asset->appraisals->last()->apr_value ?? '') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $asset->desc }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mt-5">
                        <button class="btn btn-primary btn-sm btn-block border-0" type="button" data-toggle="collapse"
                            data-target="#collapseDocument" aria-expanded="false" aria-controls="collapseDocument"
                            id="collapseButton">
                            <span>Documents</span> <i id="togglerDocument" class="fas fa-angle-right"></i>
                        </button>
                    </div>

                    <div class="col-md-4 mt-5">
                        <button class="btn btn-dark btn-sm btn-block border-0" type="button" data-toggle="collapse"
                            data-target="#collapseMaintenance" aria-expanded="false" aria-controls="collapseMaintenance"
                            id="collapseButton">
                            <span>History Maintenance</span> <i id="togglerMaintenance" class="fas fa-angle-right"></i>
                        </button>
                    </div>

                    <div class="col-md-4 mt-5">
                        <button class="btn btn-dark btn-sm btn-block border-0" type="button" data-toggle="collapse"
                            data-target="#collapseRenewal" aria-expanded="false" aria-controls="collapseRenewal"
                            id="collapseButton">
                            <span>History Renewal</span> <i id="togglerRenewal" class="fas fa-angle-right"></i>
                        </button>
                    </div>
                </div>

                <div class="collapse pb-3" id="collapseDocument">
                    <div class="">
                        <div class="d-flex align-items-end mb-4">
                            <div class="flex-grow-1">
                                <button title="add new document" class="btn btn-primary btn-sm" type="button"
                                    data-toggle="modal" data-target="#addNewRecord">
                                    Add <i class="fas fa-plus-circle"></i>
                                </button>
                            </div>

                            <img height="100" class="px-3 px-sm-4"
                                src="{{ asset('/assets/template/img/undraw_add_document_re_mbjx.svg') }}">

                            <!-- Button trigger modal -->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Doc Name</th>
                                        <th>Code Acc</th>
                                        <th>SBU</th>
                                        <th>SBU</th>
                                        <th>File</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asset->children as $child)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $child->doc_name }}</td>
                                            {{-- <td>{{ $child->document->document_group_name ?? '-' }}</td> --}}
                                            <td>{{ $child->doc_code }}</td>
                                            <td>{{ $child->sbu->sbu_name ?? '' }}</td>
                                            <td>{{ $child->sdb->sdb_name ?? '' }}</td>
                                            <td>
                                                @if ($child->file)
                                                    <a title="download file"
                                                        href="/asset-child/download/{{ $child->id }}"
                                                        class="text-dark">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-around">
                                                    <form action="/trn-renewal/create">
                                                        <input type="hidden" name="id" value="{{ $child->id }}"
                                                            readonly>
                                                        <button type="submit" class="btn btn-outline-dark btn-sm">
                                                            <i class="fas fa-file-signature"></i>New Renewal
                                                        </button>
                                                    </form>
                                                    <div>
                                                        <a title="Edit Data"
                                                            href="/asset-parent/docs/edit/{{ $asset->id }}/{{ $child->id }}"
                                                            class="btn btn-outline-dark text-xs">Edit</a>
                                                    </div>
                                                    <div>
                                                        <form action="/asset-child/{{ $child->id }}" method="POST"
                                                            id="deleteDocForm">
                                                            @csrf
                                                            @method('delete')
                                                            <button title="Delete Data"
                                                                class="btn btn-outline-danger text-xs"
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

                <div class="collapse pb-3" id="collapseMaintenance">
                    <div class="my-3">
                        <div class="table-responsive">
                            <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code Acc</th>
                                        <th>Description</th>
                                        <th>Start Date</th>
                                        <th>Due Date</th>
                                        <th>Cost</th>
                                        <th>Applicant</th>
                                        <th>Approval</th>
                                        <th>Cycle</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asset->trnMaintenance->whereNotNull('trn_value')->sortBy('trn_start_date') as $maintenance)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $maintenance->trn_no }}</td>
                                            <td>{{ strip_tags($maintenance->trn_desc) }}</td>
                                            <td>{{ createDate($maintenance->trn_start_date)->format('d/m/Y') }}</td>
                                            <td>{{ createDate($maintenance->trn_date)->format('d/m/Y') }}</td>
                                            <td class="no-wrap text-right">
                                                {{ rupiah($maintenance->trn_value) }}</td>
                                            <td>{{ $maintenance->pemohon }}</td>
                                            <td>{{ $maintenance->penyetuju }}</td>
                                            <td class="{{ $maintenance->trn_type ? 'text-info' : 'text-warning' }}">
                                                {{ $maintenance->trn_type ? 'Routine' : 'Accidentally' }}
                                            </td>
                                            <td class="{{ $maintenance->trn_status ? 'text-success' : 'text-danger' }}">
                                                {{ $maintenance->trn_status ? 'Closed' : 'Open' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="5">
                                            <b>Total</b>
                                        </td>
                                        <td class="no-wrap text-right">
                                            <b>{{ rupiah($asset->trnMaintenance->sum('trn_value')) }}</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="collapse pb-3" id="collapseRenewal">
                    <div class="my-3">
                        <div class="table-responsive">
                            <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code Acc</th>
                                        <th>Description</th>
                                        <th>Start Date</th>
                                        <th>Due Date</th>
                                        <th>Cost</th>
                                        <th>Applicant</th>
                                        <th>Approval</th>
                                        <th>Cycle</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($asset->children as $doc)
                                        @foreach ($doc->trnRenewal->whereNotNull('trn_value') as $renewal)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $renewal->trn_no }}</td>
                                                <td>{{ strip_tags($renewal->trn_desc) }}</td>
                                                <td>{{ createDate($renewal->trn_start_date)->format('d/m/Y') }}</td>
                                                <td>{{ createDate($renewal->trn_date)->format('d/m/Y') }}</td>
                                                <td class="no-wrap text-right">{{ rupiah($renewal->trn_value) }}</td>
                                                <td>{{ $renewal->pemohon }}</td>
                                                <td>{{ $renewal->penyetuju }}</td>
                                                <td class="{{ $renewal->trn_type ? 'text-info' : 'text-warning' }}">
                                                    {{ $renewal->trn_type ? 'Routine' : 'Accidentally' }}

                                                </td>
                                                <td class="{{ $renewal->trn_status ? 'text-success' : 'text-danger' }}">
                                                    {{ $renewal->trn_status ? 'Closed' : 'Open' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="5">
                                            <b>Total</b>
                                        </td>
                                        <td class="no-wrap text-right">
                                            <b>{{ rupiah($asset->sumRenewal) }}</b>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                    <h5 class="modal-title text-white" id="addNewRecordLabel">Form - Add New Documents</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAdd" action="/asset-parent/docs/add/{{ $asset->id }}" method="POST"
                        enctype="multipart/form-data">
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
                                    <label for="">Asset Name</label>
                                    <input type="text" class="form-control not-allowed"
                                        value="{{ $asset->asset_name }}" disabled>
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

                            <div class="col-md-6 mb-3">
                                <label for="sbu_id">SBU</label>
                                <select class="form-control @error('sbu_id') is-invalid @enderror" name="sbu_id"
                                    id="sbu_id">
                                    <option value=""></option>
                                    @foreach ($SBUs as $sbu)
                                        <option value="{{ $sbu->id }}"
                                            {{ old('sbu_id', $asset->sbu_id) == $sbu->id ? 'selected' : '' }}>
                                            {{ $sbu->sbu_name }}</option>
                                    @endforeach
                                </select>
                            </div>
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
            formDelete = $('#deleteForm'),
            formDeleteDoc = $('#deleteDocForm');

        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });

        $("#sbu_id").selectize({
            create: false,
            sortField: "text",
        });


        $('#collapseDocument').on('shown.bs.collapse', function() {
            $('#collapseMaintenance').collapse('hide');
            $('#collapseRenewal').collapse('hide');
            $('#togglerDocument').addClass('fa-angle-down')
            $('#togglerDocument').removeClass('fa-angle-right')
        });

        $('#collapseDocument').on('hidden.bs.collapse', function() {
            $('#togglerDocument').addClass('fa-angle-right')
            $('#togglerDocument').removeClass('fa-angle-down')
        });

        $('#collapseMaintenance').on('shown.bs.collapse', function() {
            $('#collapseDocument').collapse('hide');
            $('#collapseRenewal').collapse('hide');
            $('#togglerMaintenance').addClass('fa-angle-down')
            $('#togglerMaintenance').removeClass('fa-angle-right')
        });

        $('#collapseMaintenance').on('hidden.bs.collapse', function() {
            $('#togglerMaintenance').addClass('fa-angle-right')
            $('#togglerMaintenance').removeClass('fa-angle-down')
        });

        $('#collapseRenewal').on('shown.bs.collapse', function() {
            $('#collapseDocument').collapse('hide');
            $('#collapseMaintenance').collapse('hide');
            $('#togglerRenewal').addClass('fa-angle-down')
            $('#togglerRenewal').removeClass('fa-angle-right')
        });

        $('#collapseRenewal').on('hidden.bs.collapse', function() {
            $('#togglerRenewal').addClass('fa-angle-right')
            $('#togglerRenewal').removeClass('fa-angle-down')
        });


        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let assetId = $(this).data('asset_id');
            let id = $(this).data('id');
            formDelete.attr('action', `/asset-parent/${assetId}`)
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

        $(document).on('click', '#deleteDocButton', function(e) {
            e.preventDefault();
            let assetId = $(this).data('asset_id');
            let id = $(this).data('id');
            formDeleteDoc.attr('action', `/asset-child/${id}`)
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
                    formDeleteDoc.submit();
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
