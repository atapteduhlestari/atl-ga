@extends('layouts.master')
@section('title', 'GA | Detail Renewal Transaction')
@section('container')
    <div class="container-fluid">
        <div class="col-12 mb-3 text-center">
            <img height="100px" class="" src="{{ asset('/assets/template/img/undraw_moving_re_pipp.svg') }}">
        </div>
        {{-- <div class="d-flex flex-md-row mb-3">
            <a title="Show as PDF" href="/trn-renewal/print" class="btn btn-light btn-w-100">
                <i class="fas fa-print"></i>
            </a>
        </div> --}}

        <div class="row">
            <div class="col-md-6 mb-3">
                <table class="table table-bordered mb-3">
                    <tr>
                        <th>No.</th>
                        <td>{{ $trnRenewal->trn_no }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $trnRenewal->document->parent->group->asset_group_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Document</th>
                        <td>
                            {{ $trnRenewal->document->doc_name }} - {{ $trnRenewal->document->parent->asset_name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Cost Plan</th>
                        <td>{{ rupiah($trnRenewal->trn_value_plan) }}</td>
                    </tr>
                    <tr>
                        <th>Cost Realization</th>
                        <td>{{ rupiah($trnRenewal->trn_value) }}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ createDate($trnRenewal->trn_start_date)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        <td>{{ createDate($trnRenewal->trn_date)->format('d F Y') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 mb-3">
                <table class="table table-bordered">
                    <tr>
                        <th>ISO</th>
                        <td>{{ $trnRenewal->renewal->no_doc }}</td>
                    </tr>
                    <tr>
                        <th>SBU</th>
                        <td>{{ $trnRenewal->sbu->sbu_name }}</td>
                    </tr>
                    <tr>
                        <th>Renewal</th>
                        <td>{{ $trnRenewal->renewal->name }}</td>
                    </tr>
                    <tr>
                        <th>SDB</th>
                        <td>{{ $trnRenewal->document->sdb->sdb_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{!! $trnRenewal->trn_desc !!}</td>
                    </tr>
                    <tr>
                        <th>Cycle</th>
                        <td class="{{ $trnRenewal->trn_type ? 'text-info' : 'text-warning' }}">
                            {{ $trnRenewal->trn_type ? 'Routine' : 'Accidentally' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <div class="row">
                                <div class="col-md">
                                    @if ($trnRenewal->trn_status == 1)
                                        <button type="button" class="btn btn-sm btn-success btn-block">
                                            <i class="fas fa-check"></i> Closed
                                        </button>
                                    @else
                                        @if ($trnRenewal->trn_type)
                                            <button type="button" class="btn btn-sm btn-danger btn-block"
                                                data-toggle="modal" data-target="#setPlanModal">
                                                <i class="fas fa-exclamation"></i> Waiting Approval
                                            </button>
                                        @else
                                            <form action="/trn-renewal/update-status/{{ $trnRenewal->id }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger btn-block">
                                                    <i class="fas fa-exclamation"></i> Waiting Approval
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>File</th>
                        <td>
                            @if ($trnRenewal->file)
                                <a title="download file" href="/trn-renewal/download/{{ $trnRenewal->id }}">
                                    {{ getFileName($trnRenewal->file) }}
                                    {{-- <i class="fas fa-download"></i> --}}
                                </a>
                            @else
                                <a href="/trn-renewal/{{ $trnRenewal->id }}/edit">Add File</a>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4 mb-3">
                <label>Pembuat</label>
                <input type="button" class="form-control text-left" value="{{ $trnRenewal->user->name }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Menyetujui</label>
                <input type="button" class="form-control text-left" value="{{ $trnRenewal->penyetuju ?? '' }}" readonly>
            </div>
            <div class="col-md-4 mb-3">
                <label>Pemohon</label>
                <input type="button" class="form-control text-left" value="{{ $trnRenewal->pemohon ?? '' }}" readonly>
            </div>
        </div>
        <!-- End Row-->

    </div>

    <div class="modal fade" id="setPlanModal" tabindex="-1" aria-labelledby="setPlanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="setPlanModalLabel"> Form Plan Periode Selanjutnya
                        <br> <span class="font-weight-bold">{{ $trnRenewal->renewal->name }}</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/trn-renewal/update-status-plan/{{ $trnRenewal->id }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
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
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="/js/jquery.mask.min.js"></script>
    <script>
        $('.currency').mask('000.000.000.000', {
            reverse: true
        });
    </script>
    @if ($errors->any())
        <script>
            $('#setPlanModal').modal('show');
        </script>
    @endif
@endpush
