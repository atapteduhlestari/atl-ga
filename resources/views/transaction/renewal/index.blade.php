@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Renewal Transaction')
@section('container')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-0 text-gray-800">Transaction | Renewal</h1>

        <div class="d-flex">
            <div class="my-3 flex-grow-1">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addNewRecord">
                    Add <i class="fas fa-plus-circle"></i>
                </button>
                <a title="refresh data" class="btn btn-outline-success" href="/trn-renewal" type="button">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>

            <div class="my-3">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="pills-search-tab" data-toggle="pill" href="#pills-search" role="tab"
                            aria-controls="pills-search" aria-selected="true">
                            Search <i class="fas fa-search"></i>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" id="pills-export-tab" data-toggle="pill" href="#pills-export" role="tab"
                            aria-controls="pills-export" aria-selected="false">
                            Export <i class="fas fa-file-export"></i>
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade" id="pills-search" role="tabpanel" aria-labelledby="pills-search-tab">
                <div class="card card-body mt-3">
                    <h6 class="mb-3 font-weight-bold text-primary">Search Filter</h6>
                    <form action="/trn-renewal" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start_date">Start Date</label>
                                <div class="form-group d-flex">
                                    <input type="date" class="form-control form-control-sm" id="start_date"
                                        name="start_date" value="{{ old('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="due_date">Due Date</label>
                                <div class="form-group d-flex">
                                    <input type="date" class="form-control form-control-sm" id="due_date"
                                        name="due_date" value="{{ old('due_date') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="renewal_search_id">Type</label>
                                <select
                                    class="form-control form-control-sm @error('renewal_search_id') is-invalid @enderror"
                                    id="renewal_search_id" name="renewal_search_id">
                                    <option value="">Select Type</option>
                                    @foreach ($renewals as $mn)
                                        <option value="{{ $mn->id }}"
                                            {{ old('renewal_search_id') == $mn->id ? 'selected' : '' }}>
                                            {{ $mn->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @can('superadmin')
                                <div class="col-md-6 mb-3">
                                    <label for="sbu_search_id">SBU</label>
                                    <select class="form-control form-control-sm @error('sbu_search_id') is-invalid @enderror"
                                        id="sbu_search_id" name="sbu_search_id">
                                        <option value="">Select SBU</option>
                                        @foreach ($SBUs as $sb)
                                            <option value="{{ $sb->id }}"
                                                {{ old('sbu_search_id') == $sb->id ? 'selected' : '' }}>
                                                {{ $sb->sbu_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endcan
                            <div class="col-md-6 mb-4">
                                <label for="status">Status</label>
                                <select class="form-control form-control-sm @error('status') is-invalid @enderror"
                                    name="status" id="status">
                                    <option value=""></option>
                                    <option class="text-danger" value="false">
                                        Open
                                    </option>
                                    <option class="text-success" value="1">
                                        Closed
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <button type="submit" class="btn btn-primary rounded text-xs">
                                    Find <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- <div class="tab-pane fade" id="pills-export" role="tabpanel" aria-labelledby="pills-export-tab">
                <div class="card card-body mt-3">
                    <h6 class="mb-3 font-weight-bold text-success">Export Filter</h6>
                    <form action="/trn-renewal-export" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start_date">Start Date</label>
                                <div class="form-group d-flex">
                                    <input type="date" class="form-control form-control-sm" id="start_date"
                                        name="start_date" value="{{old('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="due_date">Due Date</label>
                                <div class="form-group d-flex">
                                    <input type="date" class="form-control form-control-sm" id="due_date"
                                        name="due_date" value="{{old('due_date') }}">
                                </div>
                            </div>

                            @can('superadmin')
                                <div class="col-md-6 mb-3">
                                    <label for="sbu_export_id">SBU</label>
                                    <select class="form-control form-control-sm @error('sbu_export_id') is-invalid @enderror"
                                        id="sbu_export_id" name="sbu_export_id">
                                        <option value="">Select SBU</option>
                                        @foreach ($SBUs as $sb)
                                            <option value="{{ $sb->id }}"
                                                {{old('sbu_export_id') == $sb->id ? 'selected' : '' }}>
                                                {{ $sb->sbu_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endcan
                            <div class="col-md-6 mb-3">
                                <label for="status">Status</label>
                                <select class="form-control form-control-sm @error('status') is-invalid @enderror"
                                    name="status" id="status">
                                    <option value=""></option>
                                    <option class="text-success" value="1">
                                        <i class="fas fa-check"></i> Approved
                                    </option>
                                    <option class="text-danger" value="false">
                                        <i class="fas fa-exclamation"></i> Waiting Approval
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md">
                                <button type="submit" class="btn btn-success rounded text-xs">
                                    Generate <i class="fas fa-file-excel"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}
        </div>

        <div class="card shadow mt-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Transaction</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Doc Name</th>
                                <th>SBU</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Cost</th>
                                <th>File</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trnRenewals as $trn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $trn->document->doc_name }} - {{ $trn->document->parent->asset_name ?? '' }}
                                    </td>
                                    <td>{{ $trn->sbu->sbu_name }}</td>
                                    <td>{{ $trn->renewal->name }}</td>
                                    <td>{!! $trn->trn_desc !!}</td>
                                    <td class="block">{{ createDate($trn->trn_date)->format('d F Y') }}</td>
                                    <td class="block">{{ rupiah($trn->trn_value) }}</td>
                                    <td>
                                        @if ($trn->file)
                                            <a title="download file" href="/trn-renewal/download/{{ $trn->id }}"
                                                class="text-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <a href="/trn-renewal/{{ $trn->id }}/edit">Add File</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <div>
                                                <a title="Detail Data" href="/trn-renewal/{{ $trn->id }}"
                                                    class="btn btn-outline-dark text-xs">
                                                    <i class="fas fa-search-plus"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <a title="Edit Data" href="/trn-renewal/{{ $trn->id }}/edit"
                                                    class="btn btn-outline-dark text-xs">Edit</a>
                                            </div>
                                            <div>
                                                <form action="/trn-renewal/{{ $trn->id }}" method="post"
                                                    id="deleteForm">
                                                    @csrf
                                                    @method('delete')
                                                    <button title="Delete Data" class="btn btn-outline-danger text-xs"
                                                        onclick="return false" id="deleteButton"
                                                        data-id="{{ $trn->id }}">
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

    <!-- Modal Assets Parent -->
    <div class="modal fade" id="addNewRecord" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="addNewRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-gradient-dark">
                    <h5 class="modal-title text-white" id="addNewRecordLabel">Form Transaction Renewal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/trn-renewal" method="POST" id="formTrnRenewal" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="asset_child_id">Select Document</label>
                                <select class="form-control @error('asset_child_id') is-invalid @enderror"
                                    id="asset_child_id" name="asset_child_id">
                                    <option value=""></option>
                                    @foreach ($assetChild as $doc)
                                        <option value="{{ $doc->id }}"
                                            {{ old('asset_child_id') == $doc->id ? 'selected' : '' }}>
                                            {{ $doc->doc_name }} - {{ $doc->parent->asset_name ?? '' }} |
                                            {{ $doc->sbu->sbu_name ?? '' }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="renewal_id">
                                    Select Renewal
                                    @can('superadmin')
                                        <a href="/renewal" class="text-xs">Add list</a>
                                    @endcan
                                </label>
                                <select class="form-control @error('renewal_id') is-invalid @enderror" id="renewal_id"
                                    name="renewal_id">
                                    <option value=""></option>
                                    @foreach ($renewals as $renewal)
                                        <option value="{{ $renewal->id }}"
                                            {{ old('renewal_id') == $renewal->id ? 'selected' : '' }}>
                                            {{ $renewal->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="trn_start_date">Start Date</label>
                                <input type="date" class="form-control @error('trn_start_date') is-invalid @enderror"
                                    name="trn_start_date" value="{{ old('trn_start_date') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="trn_date">Due Date</label>
                                <input type="date" class="form-control @error('trn_date') is-invalid @enderror"
                                    name="trn_date" value="{{ old('trn_date') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="trn_value_plan">Cost Plan</label>
                                <input type="text"
                                    class="form-control currency @error('trn_value_plan') is-invalid @enderror"
                                    name="trn_value_plan" value="{{ old('trn_value_plan') }}" autocomplete="off">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="trn_value">Cost Realization</label>
                                <input type="text"
                                    class="form-control currency @error('trn_value') is-invalid @enderror"
                                    name="trn_value" value="{{ old('trn_value') }}" autocomplete="off">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pemohon">Pemohon</label>
                                <select class="form-control @error('pemohon') is-invalid @enderror" name="pemohon"
                                    id="pemohon">
                                    <option value="">Select Employees</option>
                                    @foreach ($employees as $pemohon)
                                        <option value="{{ $pemohon->name }}"
                                            {{ old('pemohon') == $pemohon->name ? 'selected' : '' }}>
                                            {{ $pemohon->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pembuat">Pembuat</label>
                                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="penyetuju">Menyetujui</label>
                                <select class="form-control @error('penyetuju') is-invalid @enderror" name="penyetuju"
                                    id="penyetuju">
                                    <option value="">Select Employees</option>
                                    @foreach ($employees as $penyetuju)
                                        <option value="{{ $penyetuju->name }}"
                                            {{ old('penyetuju') == $penyetuju->name ? 'selected' : '' }}>
                                            {{ $penyetuju->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sbu_id">Payment SBU</label>
                                <select class="form-control @error('sbu_id') is-invalid @enderror" name="sbu_id"
                                    id="sbu_id">
                                    <option value="">Select SBU</option>
                                    @foreach ($SBUs as $sbu)
                                        <option value="{{ $sbu->id }}"
                                            {{ old('sbu_id') == $sbu->id ? 'selected' : '' }}>
                                            {{ $sbu->sbu_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="trn_type">Type</label>
                                <select class="form-control @error('trn_type') is-invalid @enderror" name="trn_type"
                                    id="trn_type">
                                    <option value="">Select Type</option>
                                    <option value="1">
                                        <i class="fas fa-check"></i> Routine
                                    </option>
                                    <option value="0">
                                        <i class="fas fa-exclamation"></i> Accidentally
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="trn_desc">Description</label>
                                    <textarea class="form-control" id="trn_desc" name="trn_desc" cols="10" rows="5">{{ old('trn_desc') }}</textarea>
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
    <script src="/js/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $('.currency').mask('000.000.000.000', {
            reverse: true
        });

        let form = $('#formTrnRenewal'),
            btnSubmit = $('#btnSubmit');

        $("#asset_search").selectize({
            create: false,
            sortField: "text",
        });

        $("#asset_child_id").selectize({
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

        // $("#sbu_export_id").selectize({
        //     create: false,
        //     sortField: "text",
        // });

        $("#pemohon").selectize({
            create: false,
            sortField: "text",
        });

        $("#penyetuju").selectize({
            create: false,
            sortField: "text",
        });

        $("#renewal_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#renewal_search_id").selectize({
            create: false,
            sortField: "text",
        });

        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });

        $('#fileInput').on('change', function(e) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });

        let formDelete = $('#deleteForm');

        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/trn-renewal/${id}`)
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
