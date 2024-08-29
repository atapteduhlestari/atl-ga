@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Maintenance Transaction')
@section('container')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex align-items-center mb-3">
            <div class="flex-grow-1">
                <h1 class="h3 mb-2 text-gray-800">Maintenance | {{ $asset->asset_name }}</h1>
            </div>
            <a href="/asset-parent/docs/{{ $asset->id }}" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="/trn-maintenance" class="btn btn-dark btn-sm">
                <i class="fas fa-external-link-square-alt"></i> Table maintenance
            </a>
        </div>
        <div class="mb-5">
            <form action="/trn-maintenance" method="POST" id="formTrnmaintenance" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                    <div class="col-md-6 mb-3">
                        <label>Asset</label>
                        <input type="text" class="form-control not-allowed" value="{{ $asset->asset_name }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="maintenance_id">
                            Select Maintenance
                            @can('superadmin')
                                <a href="/maintenance" class="text-xs">Add list</a>
                            @endcan
                        </label>
                        <select class="form-control @error('maintenance_id') is-invalid @enderror" id="maintenance_id"
                            name="maintenance_id">
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
                        <input type="date" class="form-control @error('trn_date') is-invalid @enderror" name="trn_date"
                            value="{{ old('trn_date') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trn_value_plan">Cost Plan</label>
                        <input type="text" class="form-control currency @error('trn_value_plan') is-invalid @enderror"
                            name="trn_value_plan" value="{{ old('trn_value_plan') }}" autocomplete="off">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trn_value">Cost Realization</label>
                        <input type="text" class="form-control currency @error('trn_value') is-invalid @enderror"
                            name="trn_value" value="{{ old('trn_value') }}" autocomplete="off">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pemohon">Pemohon</label>
                        <select class="form-control @error('pemohon') is-invalid @enderror" name="pemohon" id="pemohon">
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
                        <input type="text" class="form-control not-allowed" value="{{ auth()->user()->name }}" disabled>
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
                        <select class="form-control @error('sbu_id') is-invalid @enderror" name="sbu_id" id="sbu_id">
                            <option value="">Select SBU</option>
                            @foreach ($SBUs as $sbu)
                                <option value="{{ $sbu->id }}" {{ old('sbu_id') == $sbu->id ? 'selected' : '' }}>
                                    {{ $sbu->sbu_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="trn_type">Cycle</label>
                        <select class="form-control @error('trn_type') is-invalid @enderror" name="trn_type" id="trn_type">
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
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="trn_desc">Description</label>
                            <textarea class="form-control @error('trn_desc') is-invalid @enderror" id="trn_desc" name="trn_desc" cols="10"
                                rows="5">{{ old('trn_desc') }}</textarea>
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

                <button type="button" id="btnSubmit" class="btn btn-primary">Submit</button>
            </form>
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
        let form = $('#formTrnmaintenance'),
            btnSubmit = $('#btnSubmit');

        $('.currency').mask('000.000.000.000', {
            reverse: true
        });

        $("#maintenance_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#pemohon").selectize({
            create: false,
            sortField: "text",
        });

        $("#penyetuju").selectize({
            create: false,
            sortField: "text",
        });

        $("#sbu_id").selectize({
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
    </script>
@endpush
