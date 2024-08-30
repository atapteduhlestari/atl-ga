@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('title', 'GA | Edit SDB')
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">SDB / Edit / {{ $sdb->sdb_name }}</h1>

        <div class="my-4">
            <form action="/sdb/{{ $sdb->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sdb_name">SDB Name</label>
                        <input type="text" class="form-control @error('sdb_name') is-invalid @enderror" name="sdb_name"
                            id="sdb_name" value="{{ old('sdb_name', $sdb->sdb_name) }}" autocomplete="off" autofocus>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pcs_date">Purchase Date</label>
                        <input type="date" class="form-control @error('pcs_date') is-invalid @enderror" name="pcs_date"
                            id="pcs_date" value="{{ old('pcs_date', $sdb->pcs_date) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pcs_value">Purchase Value</label>
                        <input type="text" class="form-control currency @error('pcs_value') is-invalid @enderror"
                            name="pcs_value" id="pcs_value" value="{{ old('pcs_value', $sdb->pcs_value) }}">
                    </div>
                    {{-- <div class="col-md-6 mb-3">
                        <label for="due_date">Due Date</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date"
                            id="due_date" value="{{ old('due_date', $sdb->due_date) }}">
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="/sdb" class="btn btn-sm btn-secondary">
                            Back
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <script src="/js/jquery.mask.min.js"></script>

    <script>
        $('.currency').mask('000.000.000.000', {
            reverse: true
        });
    </script>
@endpush
