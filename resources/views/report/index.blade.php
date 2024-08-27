@extends('layouts.master')
@section('title', 'Report')
@section('container')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-3 text-gray-800">REPORT</h1>
        <div class="my-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card bg-light text-light ">
                        <div class="card-header"><i class="fas fa-warehouse"></i> Asset</div>
                        <div class="card-body">
                            <a class="btn btn-sm btn-info" href="/report-asset-detail">Detail</a>
                            <a class="btn btn-sm btn-primary" href="/report-asset-summary">Summary</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light text-light ">
                        <div class="card-header"><i class="fas fa-tools"></i> Maintenance</div>
                        <div class="card-body">
                            <a class="btn btn-sm btn-info" href="/report-maintenance-detail">Detail</a>
                            <a class="btn btn-sm btn-primary" href="/report-maintenance-summary">Summary</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-light text-light ">
                        <div class="card-header"><i class="fas fa-file-signature"></i> Renewal</div>
                        <div class="card-body">
                            <a class="btn btn-sm btn-info" href="/report-renewal-detail">Detail</a>
                            <a class="btn btn-sm btn-primary" href="/report-renewal-summary">Summary</a>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-4 mb-3">
                    <div class="card bg-light text-light ">
                        <div class="card-header"><i class="fas fa-file-signature"></i> Loan</div>
                        <div class="card-body">
                            <a class="btn btn-sm btn-info" href="/report-loan-detail">Detail</a>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
