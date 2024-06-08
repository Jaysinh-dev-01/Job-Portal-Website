@extends('front.layouts.app')
@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active"> <a href="{{ route('account.profile') }}">Account Settings</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    @include('front.account.sidebar')
                </div>
                <div class="col-lg-9">
                    <form action="#" method="POST" id="createJobForm" name="createJobForm">

                        <div class="card border-0 shadow mb-4 ">
                            <div class="card-body card-form p-4">
                                <h3 class="fs-4 mb-1">Job Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="title" class="mb-2">Title<span class="req">*</span></label>
                                        <input type="text" placeholder="Job Title" id="title" name="title"
                                            class="form-control">
                                        <small></small>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="category" class="mb-2">Category<span class="req">*</span></label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select Job Category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small></small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="jobType" class="mb-2">Job Type<span class="req">*</span></label>
                                        <select name="jobType" id="jobType" class="form-select">
                                            <option value="">Select Job Type</option>

                                            @if ($jobTypes->isNotEmpty())
                                                @foreach ($jobTypes as $jobType)
                                                    <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <small></small>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="vacancy" class="mb-2">Vacancy<span class="req">*</span></label>
                                        <input type="number" min="1" placeholder="Vacancy" id="vacancy"
                                            name="vacancy" class="form-control">
                                        <small></small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="salary" class="mb-2">Salary</label>
                                        <input type="text" placeholder="Salary" id="salary" name="salary"
                                            class="form-control">
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <label for="location" class="mb-2">Location<span class="req">*</span></label>
                                        <input type="text" placeholder="location" id="location" name="location"
                                            class="form-control">
                                        <small></small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="mb-2">Description<span class="req">*</span></label>
                                    <textarea class="form-control" name="description" id="description" cols="5" rows="5"
                                        placeholder="Description"></textarea>
                                    <small></small>
                                </div>
                                <div class="mb-4">
                                    <label for="benefits" class="mb-2">Benefits</label>
                                    <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="responsibility" class="mb-2">Responsibility</label>
                                    <textarea class="form-control" name="responsibility" id="responsibility" cols="5" rows="5"
                                        placeholder="Responsibility"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="qualifications" class="mb-2">Qualifications</label>
                                    <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5"
                                        placeholder="Qualifications"></textarea>
                                </div>



                                <div class="mb-4">
                                    <label for="keywords" class="mb-2">Keywords</label>
                                    <input type="text" placeholder="keywords" id="keywords" name="keywords"
                                        class="form-control">
                                </div>

                                <div class="mb-4">
                                    <label for="experience" class="mb-2">Experience<span
                                            class="req">*</span></label>
                                    <select class="form-control" name="experience" id="experience">
                                        <option value="">Your Experience</option>
                                        <option value="1">1 year</option>
                                        <option value="2">2 years</option>
                                        <option value="3">3 years</option>
                                        <option value="4">4 years</option>
                                        <option value="5">5 years</option>
                                        <option value="6">6 years</option>
                                        <option value="7">7 years</option>
                                        <option value="8">8 years</option>
                                        <option value="9">9 years</option>
                                        <option value="10">10 years</option>
                                        <option value="10+">10+ years</option>
                                    </select>
                                    <small></small>
                                </div>

                                <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="company_name" class="mb-2">Name<span
                                                class="req">*</span></label>
                                        <input type="text" placeholder="Company Name" id="company_name"
                                            name="company_name" class="form-control">
                                        <small></small>
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <label for="location" class="mb-2">Location</label>
                                        <input type="text" placeholder="Location" id="company_location" name="company_location"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="website" class="mb-2">Website</label>
                                    <input type="text" placeholder="Website" id="website" name="website"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Save Job</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('customJs')
    <script>
        $("#createJobForm").submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                type: "POST",
                url: "{{ route('account.saveJob') }}",
                data: $("#createJobForm").serializeArray(),
                dataType: "json",
                success: function(response) {

                    if (response.status == false) {
                        let errors = response.errors;

                        function addErrorByIdName(idName) {
                            if (errors[idName]) {
                                $('#' + idName).addClass('is-invalid')
                                    .siblings('small')
                                    .addClass('invalid-feedback')
                                    .html(errors[idName]);
                            } else {
                                $('#' + idName).removeClass('is-invalid')
                                    .siblings('small')
                                    .removeClass('invalid-feedback')
                                    .html(" ");
                            }
                        }

                        addErrorByIdName("title");
                        addErrorByIdName("category");
                        addErrorByIdName("jobType");
                        addErrorByIdName("vacancy");
                        addErrorByIdName("location");
                        addErrorByIdName("description");
                        addErrorByIdName("experience");
                        addErrorByIdName("company_name");

                    } else {
                        

                        function removeErrorByIdName(idName) {

                            $('#' + idName).removeClass('is-invalid')
                                .siblings('small')
                                .removeClass('invalid-feedback')
                                .html(" ");
                        }

                        removeErrorByIdName("title");
                        removeErrorByIdName("category");
                        removeErrorByIdName("Jobtype");
                        removeErrorByIdName("vacancy");
                        removeErrorByIdName("location");
                        removeErrorByIdName("description");
                        removeErrorByIdName("experience");
                        removeErrorByIdName("company_name");

                        window.location.href = "{{ route('account.myJobs') }}"
                    }


                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error("Error:", textStatus, errorThrown);
                }
            });
        });
    </script>
@endsection
