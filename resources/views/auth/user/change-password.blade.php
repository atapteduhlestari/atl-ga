@extends('layouts.master')
@push('styles')
    <link href="/assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="/assets/template/vendor/selectize/selectize.css" rel="stylesheet">
@endpush
@section('title', 'GA | Change Password')
@section('container')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
            </div>
            <div class="card-body">
                <form action="/update-password" method="POST" id="formAdd">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="Enter new password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="password" id="password"
                                class="form-control @error('password') is-invalid @enderror" name="password_confirmation"
                                id="password_confirmation" placeholder="Confirm new password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1"
                                    onclick="togglePassword()">
                                <label class="form-check-label" for="defaultCheck1">
                                    Show Password
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <!-- Page level plugins -->
    <script src="/assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/template/vendor/selectize/selectize.js"></script>
    <script>
        function togglePassword() {
            var newPassword = document.getElementById("password");
            var confirmPassword = document.getElementById("confirm_password");
            if (newPassword.type === "password") {
                newPassword.type = "text";
                confirmPassword.type = "text";
            } else {
                newPassword.type = "password";
                confirmPassword.type = "password";
            }
        }
    </script>

    @if ($errors->any())
        <script>
            $('#addNewRecord').modal('show');
        </script>
    @endif
@endpush
