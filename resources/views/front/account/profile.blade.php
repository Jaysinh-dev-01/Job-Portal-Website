@extends('front.layouts.app')
@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('account.profile') }}">Account Settings</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="card border-0 shadow mb-4">
                        <form action="" method="post" id="userForm" name="userForm">
                            <div class="card-body  p-4">
                                <h3 class="fs-4 mb-1">My Profile</h3>
                                <div class="mb-4">
                                    <label for="name" class="mb-2">Name*</label>
                                    <input type="text" name="name" id="name" placeholder="Enter Name"
                                        class="form-control" value="{{ $user->name }}" autocomplete="off">
                                    <small></small>
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="mb-2">Email*</label>
                                    <input type="text" name="email" id="email" placeholder="Enter Email"
                                        class="form-control" value="{{ $user->email }}" autocomplete="off">
                                    <small></small>
                                </div>
                                <div class="mb-4">
                                    <label for="designation" class="mb-2">Designation</label>
                                    <input type="text" name="designation" id="designation" placeholder="Designation"
                                        class="form-control" value="{{ $user->designation }}" autocomplete="off">
                                </div>
                                <div class="mb-4">
                                    <label for="mobile" class="mb-2">Mobile</label>
                                    <input type="text" name="mobile" id="mobile" placeholder="Mobile"
                                        class="form-control" value="{{ $user->mobile }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>

                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <form action="" method="POST" id="changePasswordForm" name="changePasswordForm">
                                <h3 class="fs-4 mb-1">Change Password</h3>
                                <div class="mb-4">
                                    <label for="password" class="mb-2">Old Password*</label>
                                    <input type="password" id="old_password" name="old_password" placeholder="Old Password"
                                        class="form-control" autocomplete="off">
                                    <small></small>
                                </div>
                                <div class="mb-4">
                                    <label for="new_password" class="mb-2">New Password*</label>
                                    <input type="password" id="new_password" name="new_password" placeholder="New Password"
                                        class="form-control" autocomplete="off">
                                    <small></small>
                                </div>
                                <div class="mb-4">
                                    <label for="confirm_password" class="mb-2">Confirm Password*</label>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                        placeholder="Confirm Password" class="form-control" autocomplete="off">
                                    <small></small>
                                </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (Session::has('success') || Session::has('error'))
        @include('sweetalert::alert');
    @endif
@endsection


@section('customJs')
    <script>
        $("#userForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "PUT",
                url: "{{ route('account.profile.update') }}",
                data: $("#userForm").serializeArray(),
                dataType: "json",
                success: function(response) {

                    if (response.status == false) {
                        let errors = response.errors;
                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings('small')
                                .addClass('invalid-feedback')
                                .html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings('small')
                                .removeClass('invalid-feedback')
                                .html(" ");
                        }
                        // Error for email
                        if (errors.email) {
                            console.log("Working");
                            $("#email").addClass('is-invalid')
                                .siblings('small')
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings('small')
                                .removeClass('invalid-feedback')
                                .html(" ");
                        }
                    } else {

                        $("#name").removeClass('is-invalid')
                            .siblings('small')
                            .removeClass('invalid-feedback')
                            .html(" ");

                        $("#email").removeClass('is-invalid')
                            .siblings('small')
                            .removeClass('invalid-feedback')
                            .html(" ");

                        window.location.href = "{{ route('account.profile') }}";

                    }

                }
            });
        });


        $("#changePasswordForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('account.updatePassword') }}",
                data: $("#changePasswordForm").serializeArray(),
                dataType: "json",
                success: function(response) {
                    if (response.status == false) {
                        let errors = response.errors;
                        console.log(errors);
                        if (errors.old_password) {
                            $("#old_password").addClass('is-invalid')
                                .siblings('small')
                                .addClass('invalid-feedback')
                                .html(errors.old_password);
                        } else {
                            $("#old_password").removeClass('is-invalid')
                                .siblings('small')
                                .removeClass('invalid-feedback')
                                .html(" ");
                        }

                        if (errors.new_password) {
                            $("#new_password").addClass('is-invalid')
                                .siblings('small')
                                .addClass('invalid-feedback')
                                .html(errors.new_password);
                        } else {
                            $("#new_password").removeClass('is-invalid')
                                .siblings('small')
                                .removeClass('invalid-feedback')
                                .html(" ");
                        }

                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid')
                                .siblings('small')
                                .addClass('invalid-feedback')
                                .html(errors.confirm_password);
                        } else {
                            $("#confirm_password").removeClass('is-invalid')
                                .siblings('small')
                                .removeClass('invalid-feedback')
                                .html(" ");
                        }

                    } else {
                            console.log("Working fine 1");
                            $("#old_password").removeClass('is-invalid')
                            .siblings('small')
                            .removeClass('invalid-feedback')
                            .html(" ");
                            
                            console.log("Working fine 2");
                            $("#new_password").removeClass('is-invalid')
                            .siblings('small')
                            .removeClass('invalid-feedback')
                            .html(" ");
                            console.log("Working fine 3");
                            
                        $("#confirm_password").removeClass('is-invalid')
                            .siblings('small')
                            .removeClass('invalid-feedback')
                            .html(" ");

                            window.location.reload();
                    }
                }
            });
        });
    </script>
@endsection
