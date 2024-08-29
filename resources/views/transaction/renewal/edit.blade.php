@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Edit Renewal Transaction')
@section('container')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex align-items-center mb-3">
            <div class="flex-grow-1">
                <h1 class="h3 mb-2 text-gray-800">Transaction Renewal / Edit /
                    {{ $trnRenewal->document->parent->asset_name ? $trnRenewal->document->parent->asset_name . ' / ' : ' ' }}
                    {{ $trnRenewal->document->doc_name }}</h1>
            </div>
        </div>
        <div class="mb-5">
            <form action="/trn-renewal/{{ $trnRenewal->id }}" method="POST" id="formTrnRenewal"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <input type="hidden" name="asset_child_id" value="{{ $trnRenewal->asset_child_id }}">
                    <div class="col-md-6 mb-3">
                        <label>Document</label>
                        <input type="text" class="form-control not-allowed" value="{{ $trnRenewal->document->doc_name }}"
                            readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="renewal_id">Select Renewal</label>
                        <select class="form-control @error('renewal_id') is-invalid @enderror" id="renewal_id"
                            name="renewal_id">
                            <option value=""></option>
                            @foreach ($renewals as $renewal)
                                <option value="{{ $renewal->id }}"
                                    {{ old('renewal_id', $trnRenewal->renewal_id) == $renewal->id ? 'selected' : '' }}>
                                    {{ $renewal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="trn_start_date">Start Date</label>
                        <input type="date" class="form-control @error('trn_start_date') is-invalid @enderror"
                            name="trn_start_date" value="{{ old('trn_start_date', $trnRenewal->trn_start_date) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trn_date">Due Date</label>
                        <input type="date" class="form-control @error('trn_date') is-invalid @enderror" name="trn_date"
                            value="{{ old('trn_date', $trnRenewal->trn_date) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trn_value_plan">Cost Plan</label>
                        <input type="text" class="form-control currency @error('trn_value_plan') is-invalid @enderror"
                            name="trn_value_plan" value="{{ old('trn_value_plan', $trnRenewal->trn_value_plan) }}"
                            autocomplete="off">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trn_value">Cost Realization</label>
                        <input type="text" class="form-control currency @error('trn_value') is-invalid @enderror"
                            name="trn_value" value="{{ old('trn_value', $trnRenewal->trn_value) }}" autocomplete="off">
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
                                    {{ old('pemohon', $trnRenewal->pemohon) == $pemohon->name ? 'selected' : '' }}>
                                    {{ $pemohon->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="pembuat">Pembuat</label>
                        <input type="text" class="form-control not-allowed" value="{{ $trnRenewal->user->name }}"
                            disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="penyetuju">Menyetujui</label>
                        <select class="form-control @error('penyetuju') is-invalid @enderror" name="penyetuju"
                            id="penyetuju">
                            <option value="">Select Employees</option>
                            @foreach ($employees as $penyetuju)
                                <option value="{{ $penyetuju->name }}"
                                    {{ old('penyetuju', $trnRenewal->penyetuju) == $penyetuju->name ? 'selected' : '' }}>
                                    {{ $penyetuju->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sbu_id">Payment SBU</label>
                        <select class="form-control @error('sbu_id') is-invalid @enderror" name="sbu_id" id="sbu_id">
                            <option value="">Select SBU</option>
                            @foreach ($SBUs as $sbu)
                                <option value="{{ $sbu->id }}"
                                    {{ old('sbu_id', $trnRenewal->sbu_id) == $sbu->id ? 'selected' : '' }}>
                                    {{ $sbu->sbu_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="trn_type">Type</label>
                        <select class="form-control @error('trn_type') is-invalid @enderror" name="trn_type" id="trn_type">
                            <option value="1" {{ $trnRenewal->trn_type ? 'selected' : '' }}>
                                <i class="fas fa-check"></i> Cycle
                            </option>
                            <option value="0" {{ !$trnRenewal->trn_type ? 'selected' : '' }}>
                                <i class="fas fa-exclamation"></i> Accidentally
                            </option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="trn_desc">Description</label>
                            <textarea class="form-control  @error('trn_desc') is-invalid @enderror" id="trn_desc" name="trn_desc" cols="10"
                                rows="5">{{ old('trn_desc', strip_tags($trnRenewal->trn_desc)) }}</textarea>
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
                        @if ($trnRenewal->file)
                            <small> old file : <a old file :a href="/trn-renewal/download/{{ $trnRenewal->id }}">
                                    {{ getFileName($trnRenewal->file ?? '') }}</a></small>
                        @endif
                    </div>
                </div>

                <a href="/trn-renewal/{{ $trnRenewal->id }}" class="btn btn-secondary btn-sm mr-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="button" id="btnSubmit" class="btn btn-sm btn-primary">Submit</button>
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
    <script src="/assets/app/js/table.js"></script>
    <script>
        let form = $('#formTrnRenewal'),
            btnSubmit = $('#btnSubmit');

        $('.currency').mask('000.000.000.000', {
            reverse: true
        });

        $("#renewal_id").selectize({
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
