@extends('front.layouts.app')
@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.users') }}">Users</a></li>
                            <li class="breadcrumb-item active">Edit User</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form">
                            <form action="" method="post" id="userForm" name="userForm">
                                <div class="card-body  p-4">
                                    <h3 class="fs-4 mb-1">User Profile</h3>
                                    <div class="mb-4">
                                        <label for="name" class="mb-2">User Name*</label>
                                        <input type="text" name="name" id="name" placeholder="Enter Name"
                                            class="form-control" value="{{ $user->name }}" autocomplete="off">
                                        <small></small>
                                    </div>
                                    <div class="mb-4">
                                        <label for="email" class="mb-2">User Email*</label>
                                        <input type="text" name="email" id="email" placeholder="Enter Email"
                                            class="form-control" value="{{ $user->email }}" autocomplete="off">
                                        <small></small>
                                    </div>
                                    <div class="mb-4">
                                        <label for="designation" class="mb-2">User Mobile</label>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('customJs')
    <script>
        $("#userForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "put",
                url: '{{ route('admin.updateUser', $user->id) }}',
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

                        window.location.href = "{{ route('admin.users') }}";

                    }

                }
            });
        });
    </script>
@endsection
