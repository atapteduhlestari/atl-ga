@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Maintenance Transaction')
@section('container')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-0 text-gray-800">Transaction Maintenance</h1>

        <div class="d-flex">
            <div class="my-3 flex-grow-1">
                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addNewRecord">
                    Add <i class="fas fa-plus-circle"></i>
                </button>
                <a title="refresh data" class="btn btn-outline-success" href="/trn-maintenance" type="button">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>

            <div class="my-3">
                <button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapseSearch"
                    aria-expanded="false" aria-controls="collapseSearch">
                    Filter Search
                </button>
            </div>
        </div>

        <div class="collapse" id="collapseSearch">
            <div class="card card-body mt-3">
                <h6 class="mb-3 font-weight-bold text-primary">Search Filter</h6>
                <form action="/trn-maintenance" method="get">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="start_date">Transaction Start Date</label>
                            <div class="form-group d-flex">
                                <input type="date" class="form-control form-control-sm" id="start_date" name="start_date"
                                    value="{{ old('start_date') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="due_date">Transaction Due Date</label>
                            <div class="form-group d-flex">
                                <input type="date" class="form-control form-control-sm" id="due_date" name="due_date"
                                    value="{{ old('due_date') }}">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="asset_search_id">Asset</label>
                            <select class="form-control form-control-sm @error('asset_search_id') is-invalid @enderror"
                                id="asset_search_id" name="asset_search_id">
                                <option value="">Select Asset</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}"
                                        {{ old('asset_search_id') == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->asset_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="maintenance_search_id">Type</label>
                            <select
                                class="form-control form-control-sm @error('maintenance_search_id') is-invalid @enderror"
                                id="maintenance_search_id" name="maintenance_search_id">
                                <option value="">Select Type</option>
                                @foreach ($maintenances as $mn)
                                    <option value="{{ $mn->id }}"
                                        {{ old('maintenance_search_id') == $mn->id ? 'selected' : '' }}>
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

                        <div class="col-md-6 mb-4">
                            <label for="trn_type">Cycle</label>
                            <select class="form-control form-control-sm @error('trn_type') is-invalid @enderror"
                                name="trn_type" id="trn_type">
                                <option value=""></option>
                                <option class="text-info" value="1">
                                    Routine
                                </option>
                                <option class="text-warning" value="false">
                                    Accidentally
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

        <div class="card shadow mt-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Table Data</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Asset Name</th>
                                <th>SBU</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Cycle</th>
                                <th>File</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trnMaintenances as $key => $trn)
                                <tr>
                                    <td>{{ $loop->index + $trnMaintenances->firstItem() }}</td>
                                    <td>
                                        <a href="/asset-parent/docs/{{ $trn->asset->id }}">{{ $trn->asset->asset_name }}
                                        </a>
                                    </td>
                                    <td>{{ $trn->sbu->sbu_name }}</td>
                                    <td>{{ $trn->maintenance->name }}</td>
                                    <td>{!! $trn->trn_desc !!}</td>
                                    <td class="block">{{ createDate($trn->trn_date)->format('d F Y') }}</td>
                                    <td class="block">{{ rupiah($trn->trn_value) }}</td>
                                    <td class=" {{ $trn->trn_status ? 'text-success' : 'text-danger' }} block">
                                        {{ $trn->trn_status ? 'Closed' : 'Open' }}
                                    </td>
                                    <td class=" {{ $trn->trn_type ? 'text-info' : 'text-warning' }} block">
                                        {{ $trn->trn_type ? 'Routine' : 'Accidentally' }}
                                    </td>
                                    <td>
                                        @if ($trn->file)
                                            <a title="download file" href="/trn-maintenance/download/{{ $trn->id }}"
                                                class="text-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <a href="/trn-maintenance/{{ $trn->id }}/edit">Add File</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <div>
                                                <a title="Detail Data" href="/trn-maintenance/{{ $trn->id }}"
                                                    class="btn btn-outline-dark text-xs">
                                                    <i class="fas fa-search-plus"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <a title="Edit Data" href="/trn-maintenance/{{ $trn->id }}/edit"
                                                    class="btn btn-outline-dark text-xs">Edit</a>
                                            </div>
                                            <div>
                                                <form action="/trn-maintenance/{{ $trn->id }}" method="post"
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
                <div class="row">
                    <div class="col-sm-12 col-md-5 mb-3">
                        <div class="text-left">
                            Showing
                            {{ $trnMaintenances->firstItem() }}
                            to
                            {{ $trnMaintenances->lastItem() }}
                            of
                            {{ $trnMaintenances->total() }}
                            entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 mx-auto">
                        <div class="text-right float-right">
                            {{ $trnMaintenances->onEachSide(0)->links() }}
                        </div>
                    </div>
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
                    <h5 class="modal-title text-white" id="addNewRecordLabel">Form Transaction Maintenance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/trn-maintenance" method="POST" id="formTrnmaintenance"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="asset_id">Select Asset</label>
                                <select class="form-control @error('asset_id') is-invalid @enderror" id="asset_id"
                                    name="asset_id">
                                    <option value=""></option>
                                    @foreach ($assets as $asset)
                                        <option value="{{ $asset->id }}"
                                            {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->sbu->sbu_name ?? '' }} | {{ $asset->asset_name }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="maintenance_id">
                                    Select Maintenance
                                    @can('superadmin')
                                        <a href="/maintenance" class="text-xs">Add list</a>
                                    @endcan
                                </label>
                                <select class="form-control @error('maintenance_id') is-invalid @enderror"
                                    id="maintenance_id" name="maintenance_id">
                                    <option value=""></option>
                                    @foreach ($maintenances as $maintenance)
                                        <option value="{{ $maintenance->id }}"
                                            {{ old('maintenance_id') == $maintenance->id ? 'selected' : '' }}>
                                            {{ $maintenance->name }}</option>
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
                                <label for="trn_type">Cycle</label>
                                <select class="form-control @error('trn_type') is-invalid @enderror" name="trn_type"
                                    id="trn_type">
                                    <option value="">Select Cycle</option>
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
    <script src="/assets/template/vendor/selectize/selectize.js"></script>
    <script src="/js/jquery.mask.min.js"></script>
    <script>
        $('.currency').mask('000.000.000.000', {
            reverse: true
        });

        let form = $('#formTrnmaintenance'),
            btnSubmit = $('#btnSubmit');

        $("#asset_search_id").selectize({
            create: false,
            sortField: "text",
        });

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

        $("#maintenance_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#maintenance_search_id").selectize({
            create: false,
            sortField: "text",
        });

        $('#fileInput').on('change', function(e) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });

        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });

        let formDelete = $('form#deleteForm');

        $(document).on('click', '#deleteButton', function(e) {
            console.log('ok');
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/trn-maintenance/${id}`)
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
