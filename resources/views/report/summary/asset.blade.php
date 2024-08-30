@extends('layouts.master')
@section('title', 'GA | Asset Transaction')
@section('container')
    <div class="container-fluid">
        <a href="/report" class="btn btn-secondary btn-sm mr-2">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="card card-body mt-3">
            <h6 class="mb-3 font-weight-bold text-primary">Report Asset Summary</h6>
            <form action="/asset-summary-export" method="get">
                <div class="row">
                    <div class="col-md-6">
                        <label for="start">Purchase Date Start</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('start') is-invalid @enderror"
                                id="start" name="start" value="{{ request('start') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="end">Purchase Date End</label>
                        <div class="form-group d-flex">
                            <input type="date" class="form-control form-control-sm @error('end') is-invalid @enderror"
                                id="end" name="end" value="{{ request('end') }}">
                        </div>
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control form-control-sm @error('status') is-invalid @enderror" name="status"
                            id="status">
                            <option value=""></option>
                            <option class="text-primary" value="1">
                                <i class="fas fa-check"></i> Approved
                            </option>
                            <option class="text-danger" value="false">
                                <i class="fas fa-exclamation"></i> Waiting Approval
                            </option>
                        </select>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md">
                        <button type="submit" class="btn btn-primary rounded text-xs">
                            Generate <i class="fas fa-file-excel"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
