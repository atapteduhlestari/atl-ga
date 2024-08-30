@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('title', 'GA | ' . $sdb->sdb_name)
@section('container')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">{{ $sdb->sdb_name }}</h1>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Item</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th>Asset</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sdb->docs as $doc)
                                <tr>
                                    <td>
                                        {{ $doc->doc_name }}
                                    </td>
                                    <td>
                                        @if ($doc->parent)
                                            <a href="/asset-parent/docs/{{ $doc->asset_id }}">
                                                {{ $doc->parent->asset_name }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <form action="/sdb/remove-document/{{ $doc->id }}" method="post"
                                                id="deleteForm">
                                                @csrf
                                                <button title="Delete Data" class="btn btn-outline-danger btn-sm"
                                                    onclick="return false" id="deleteButton" data-id="{{ $doc->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="/sdb" class="btn btn-secondary btn-sm mt-3">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        let formDelete = $('#deleteForm');

        $(document).ready(function() {
            $('#dataTable').DataTable({
                "searching": false,
                "lengthChange": false,
                "pageLength": 50
            });
        });

        $(document).on('click', '#deleteButton', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            formDelete.attr('action', `/sdb/remove-document/${id}`)
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
@endpush
