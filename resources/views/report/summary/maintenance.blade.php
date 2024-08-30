@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Maintenance Transaction')
@section('container')
    <div class="container-fluid">
        <a href="/report" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="card card-body mt-3">
            <h6 class="mb-3 font-weight-bold text-primary">Report Maintenance Summary</h6>
            <form action="/trn-maintenance-summary-export" method="get">
                <div class="row">
                    <div class="col-md-6">
                        <label for="start">Transaction Start Date</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('start') is-invalid @enderror"
                                id="start" name="start" value="{{ old('start') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end">Transaction Due Date</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('end') is-invalid @enderror"
                                id="end" name="end" value="{{ old('end') }}">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="asset_id">Asset</label>
                        <select class="form-control form-control-sm @error('asset_id') is-invalid @enderror" id="asset_id"
                            name="asset_id">
                            <option value="">Select Asset</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->asset_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="maintenance_id">Type</label>
                        <select class="form-control form-control-sm @error('maintenance_id') is-invalid @enderror"
                            id="maintenance_id" name="maintenance_id">
                            <option value="">Select Type</option>
                            @foreach ($maintenances as $maintenance)
                                <option value="{{ $maintenance->id }}"
                                    {{ old('maintenance_id') == $maintenance->id ? 'selected' : '' }}>
                                    {{ $maintenance->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @can('superadmin')
                        <div class="col-md-6 mb-3">
                            <label for="sbu_id">SBU</label>
                            <select class="form-control form-control-sm @error('sbu_id') is-invalid @enderror" id="sbu_id"
                                name="sbu_id">
                                <option value="">Select SBU</option>
                                @foreach ($SBUs as $sb)
                                    <option value="{{ $sb->id }}" {{ old('sbu_id') == $sb->id ? 'selected' : '' }}>
                                        {{ $sb->sbu_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endcan
                    <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control form-control-sm @error('status') is-invalid @enderror" name="status"
                            id="status">
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
                        <select class="form-control form-control-sm @error('trn_type') is-invalid @enderror" name="trn_type"
                            id="trn_type">
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
                        <button type="submit" class="btn btn-info rounded text-xs">
                            Generate <i class="fas fa-file-excel"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/selectize/selectize.js"></script>
    <script>
        $("#sbu_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#asset_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#maintenance_id").selectize({
            create: false,
            sortField: "text",
        });
    </script>
@endpush
