@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Asset Transaction')
@section('container')
    <div class="container-fluid">
        <a href="/report" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="card card-body mt-3">
            <h6 class="mb-3 font-weight-bold text-info">Report Asset Detail</h6>
            <form action="/asset-detail-export" method="get">
                <div class="row">
                    <div class="col-md-6">
                        <label for="start">Purchase Date Start</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('start') is-invalid @enderror"
                                id="start" name="start" value="{{ old('start') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end">Purchase Date End</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('end') is-invalid @enderror"
                                id="end" name="end" value="{{ old('end') }}">
                        </div>
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
                        <label for="condition">Condition</label>
                        <select class="form-control @error('condition') is-invalid @enderror" name="condition"
                            id="condition">
                            <option value=""></option>
                            <option class="text-success" value="1" {{ old('condition') == 1 ? 'selected' : '' }}>
                                Excellent
                            </option>
                            <option class="text-warning" value="2" {{ old('condition') == 2 ? 'selected' : '' }}>
                                Fair
                            </option>
                            <option class="text-danger" value="3" {{ old('condition') == 3 ? 'selected' : '' }}>
                                Poor
                            </option>
                            <option class="text-dark" value="4" {{ old('condition') == 4 ? 'selected' : '' }}>
                                Disposed
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
    </script>
@endpush
