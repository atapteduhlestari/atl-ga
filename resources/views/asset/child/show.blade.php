@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | History Renewal')
@section('container')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-0 text-gray-800">History Renewal / {{ $children->doc_name }} /
            {{ $children->parent->asset_name ?? '' }}
        </h1>

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
                                <th>Doc Name</th>
                                <th>Asset</th>
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
                            @foreach ($children->trnRenewal as $trn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $trn->document->doc_name }}
                                    </td>
                                    <td>
                                        @if ($trn->document->parent)
                                            <a href="/asset-parent/docs/{{ $trn->document->asset_id }}">
                                                {{ $trn->document->parent->asset_name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $trn->sbu->sbu_name }}</td>
                                    <td>{{ $trn->renewal->name }}</td>
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
                                            <a title="download file" href="/trn-renewal/download/{{ $trn->id }}"
                                                class="text-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <a href="/trn-renewal/{{ $trn->id }}/edit">Add File</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-around">
                                            <div>
                                                <a title="Detail Data" href="/trn-renewal/{{ $trn->id }}"
                                                    class="btn btn-outline-dark text-xs">
                                                    <i class="fas fa-search-plus"></i>
                                                </a>
                                            </div>
                                            <div>
                                                <a title="Edit Data" href="/trn-renewal/{{ $trn->id }}/edit"
                                                    class="btn btn-outline-dark text-xs">Edit</a>
                                            </div>
                                            <div>
                                                <form action="/trn-renewal/{{ $trn->id }}" method="post"
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

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/selectize/selectize.js"></script>
    <script src="/js/jquery.mask.min.js"></script>
    <script>
        $('.currency').mask('000.000.000.000', {
            reverse: true
        });

        let form = $('#formTrnRenewal'),
            btnSubmit = $('#btnSubmit');

        $("#asset_search").selectize({
            create: false,
            sortField: "text",
        });

        $("#asset_child_id").selectize({
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

        $("#renewal_id").selectize({
            create: false,
            sortField: "text",
        });

        $("#renewal_search_id").selectize({
            create: false,
            sortField: "text",
        });

        btnSubmit.click(function() {
            $(this).prop('disabled', true);
            form.submit();
        });

        $('#fileInput').on('change', function(e) {
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(e.target.files[0].name);
        });

        let formDelete = $('form#deleteForm');

        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/trn-renewal/${id}`)
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
