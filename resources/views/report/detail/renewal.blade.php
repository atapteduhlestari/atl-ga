@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Renewal Transaction')
@section('container')
    <div class="container-fluid">
        <a href="/report" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <button class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#RenewalPlanModal">
            <i class="far fa-lightbulb"></i> Plan
        </button>
        <div class="card card-body mt-3">
            <h6 class="mb-3 font-weight-bold text-info">Report Renewal Detail</h6>
            <form action="/trn-renewal-detail-export" method="get">
                <div class="row">
                    <div class="col-md-6">
                        <label for="start">Start</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('start') is-invalid @enderror"
                                id="start" name="start" value="{{ old('start') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end">End</label>
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

    <!-- Modal -->
    <div class="modal fade" id="RenewalPlanModal" tabindex="-1" aria-labelledby="RenewalPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RenewalPlanModalLabel">Renewal Plan Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/trn-renewal-plan-export" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start">Start</label>
                                <div class="form-group d-flex">
                                    <input type="date"
                                        class="form-control form-control-sm @error('start') is-invalid @enderror"
                                        id="start" name="start" value="{{ old('start') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="end">End</label>
                                <div class="form-group d-flex">
                                    <input type="date"
                                        class="form-control form-control-sm @error('end') is-invalid @enderror"
                                        id="end" name="end" value="{{ old('end') }}">
                                </div>
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
            </div>
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
